<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Dog;
use App\Models\Type;
use App\Models\Gender;
use App\Models\Selter;
use UnableToWriteFile;
use App\Models\Adoption;
use Illuminate\Support\Str;
use App\Models\Sterlisation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\DogResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\DogUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DogCreatedRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Exceptions\HttpResponseException;

class DogController extends Controller
{

    private $durasi = 10;
    private  $timeThreshold ;
    private $day = 1;

    private function getCachedData($key, $id, $model, $errorMessage)
    {
        $data = Cache::get($key . $id);

        if (!$data) {
            $data = $model::find($id);

            if (!$data) {
                throw new HttpResponseException(response()->json([
                    'errors' => [
                        'message' => [
                            $errorMessage
                        ]
                    ]
                ])->setStatusCode(404));
            }

            // Simpan data ke dalam cache dengan durasi 10 menit
            Cache::put($key . $id, $data, now()->addMinutes($this->durasi));
        }

        return $data;
    }

    public function create(DogCreatedRequest $request): JsonResponse
    {
        $data = $request->validated();
        // kita gunakan function unutk menyimpan data gambar ke google cloud storage


        $fileUploud = new GoogleCloudStorageController();

        $url = $fileUploud->uploadFile($request);

        $dog = Dog::create([
            'name' =>  $request->name,
            'age' => $request->age,
            'rescue_story' => $request->character,
            'picture' =>  $url,
            'type_id' => $request->type_id,
            'character' => $request->character,
            'gender' => $request->gender,
            'selter_id' => $request->selter_id,
            'steril_id' => $request->steril_id
        ]);
        return (new DogResource($dog))->response()->setStatusCode(201);
    }

    public function get(Request $request): JsonResponse
    {
        $start = microtime(true);
        $dog = $this->getCachedData('dog_', $request->id, Dog::class, 'Dog not found');
        $selter = $this->getCachedData('selter_', $dog->selter_id, Selter::class, 'Selter not found');
        $steril = $this->getCachedData('steril_', $dog->steril_id, Sterlisation::class, 'Sterilisation not found');
        $type = $this->getCachedData('type_', $dog->type_id, Type::class, 'Type not found');
        $end = microtime(true);

        $executionTime = ($end - $start) * 1000 . 'ms';
        return response()->json([
            'data' => [
                'time' => $executionTime,
                'dog' => $dog,
                'selter' => $selter,
                'gender' => $steril,
                'Type' => $type
            ]
        ])->setStatusCode(200);
    }

    public function update(DogUpdateRequest $request): DogResource
    {
        $data =  $request->validated();
        $dog = Cache::remember('dogUpdate_' . $request->id, now()->addMinutes($this->durasi), function () use ($request) {
            return Dog::where('id', $request->id)->first();
        });


        if (!$dog) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
       
        if($request->hasFile('picture')){
            //medelet gambar yang ada di google cloud storage 
            $storage =  new GoogleCloudStorageController();
            $delete =  $storage->deleteFile($dog->picture);

            if ($delete == 404){
                throw new HttpResponseException(response()->json([
                    "errors" => [
                        "message" => [
                            "not found"
                        ]
                    ]
                ])->setStatusCode(404));
            } else if ($delete ==  500){
                throw new HttpResponseException(response()->json([
                    "errors" => [
                        "message" => [
                            "Server Storage not responding"
                        ]
                    ]
                ])->setStatusCode(500));
            }

             // melakuakn uploud kemabali image jika ada perubahan
             $patch =  $storage->uploadFile($request, 'dog-image');
             $data['picture'] = $patch;
        }else {
            //misalnya dia tidak meruba gambar selter maka diisi dengan patch gambar lama
            $data['picture'] = $dog->picture;
        }


        $dog->fill($data);
        $dog->save();

        return new DogResource($dog);
    }

    public function delete(Request $request): JsonResponse
    {
        $dog = Dog::where('id', $request->id)->first();
        if (!$dog) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $storage =  new GoogleCloudStorageController();
        $delete =  $storage->deleteFile($dog->picture);
        $dog->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function search(Request $request)
    {
        $this->timeThreshold =  now()->subDay($this->day);
        // Melakukan validasi sehingga inputan search harus berupa string
        $validate = Validator::make($request->all(), [
            'query' => 'string',
        ]);

        if ($validate->fails()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validate->errors(),
            ])->setStatusCode(400));
        }

        $query = $request->input('query');

        //lakukan pengechekan data apakah ada data baru
        $newOrUpdateData = Dog::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Dog::where('deleted_at','>',$this->timeThreshold)->get();

        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('search_results_' .  md5($query));
        }

        // Menggunakan cache dengan kunci yang unik berdasarkan query
        $results = Cache::remember('search_results_' . md5($query), now()->addMinutes($this->durasi), function () use ($query, $request) {
            return Dog::select('dogs.*', 'types.type', 'types.size', 'types.activity_level', 'types.groups', 'selters.name as selter_name', 'selters.address as address')
                ->join('sterlisations', 'dogs.steril_id', 'sterlisations.id')
                ->join('types', 'dogs.type_id', 'types.id')
                ->join('selters', 'dogs.selter_id', '=', 'selters.id')
                ->orWhere('dogs.name', 'like', '%' . $query . '%')
                ->orWhere('dogs.age', 'like', '%' . $query . '%')
                ->orWhere('dogs.character', 'like', '%' . $query . '%')
                ->orWhere('dogs.gender', 'like', '%' . $query . '%')
                ->orWhere('selters.name', 'like', '%' . $query . '%')
                ->orWhere('selters.address', 'like', '%' . $query . '%')
                ->orWhere('types.groups', 'like', '%' . $query . '%')
                ->paginate($request->limit, ['*'], 'page', $request->page);
        });

        if (!$results) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }
        $end = microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms'; // Konversi ke milidetik
        return response()->json([
            "error" => false,
            "message" => "success",
            "time" => $executionTime,
            "data" => $results,

        ])->setStatusCode(200);
    }


    public function updateLabel($rescue)
    {
        if ($rescue != null) {

            // Menandai status data yang sudah diambil
            Dog::where('id', $rescue->id)->update(['reads' => true]);
            return true;
        }

        return false;
    }

    public function getRescue(): JsonResponse
    {
        // Mendapatkan rescue secara acak dari cache atau database

        $rescue = Dog::select('id', 'rescue_story')->where('reads', false)->inRandomOrder()->first();


        // Jika tidak ada rescue yang ditemukan, berikan respons JSON dengan status 404
        if (!$rescue) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }

        // Mengupdate label dari false ke true
        $checkUpdate = $this->updateLabel($rescue);

        if (!$checkUpdate) {
            return response()->json([
                'error' =>  false,
                'message' => "server not responding"
            ])->setStatusCode(500);
        }

        // Memberikan respons JSON dengan data rescue
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $rescue
        ])->setStatusCode(200);
    }




    public function  filter(Request $request): JsonResponse

    { 
        //inisiasi time Threshold untuk mendeteksi adanya perubahan dalam
        $this->timeThreshold =  now()->subDay($this->day);
    
        // Membuat instance query builder untuk model Dog
        $query = Dog::query();

        // Filter berdasarkan jenis
        if ($request->has("groups")) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('groups', $request->input('groups'));
            });
        }

        // Filter berdasarkan sterilisasi
        if ($request->has("sterlisasi")) {
            $query->whereHas('sterlisation', function ($q) use ($request) {
                $q->where('name', $request->input('sterlisasi'));
            });
        }

        // Filter berdasarkan usia
        if ($request->has("age")) {
            $query->where('age', $request->input('age'));
        }

        //lakukan pengechekan data apakah ada data baru
        $newOrUpdateData = Dog::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Dog::where('deleted_at','>',$this->timeThreshold)->get();
        $newOrUpdateDataAdop = Adoption::where('updated_at','>', $this->timeThreshold)->get();
        $deleteDataAdop = Adoption::where('deleted_at','>',$this->timeThreshold)->get();

        //jika ada perubahan maka dicheck jika ada perubahan maka otomatis mengabil data yang baru
        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty() || $newOrUpdateDataAdop->isNotEmpty() || $deleteDataAdop->isNotEmpty()) {
            Cache::forget('search_dogs_' . md5(json_encode($request->all())));
        }

        // Menggunakan cache dengan kunci yang unik berdasarkan parameter filter
        $dogs = Cache::remember('search_dogs_' . md5(json_encode($request->all())), now()->addMinutes(10), function () use ($query, $request) {
            return $query->select('dogs.id','dogs.name','dogs.picture','dogs.gender','dogs.age','types.type')
            ->join('types','dogs.type_id','=','types.id')
            ->leftJoin('adoptions','adoptions.dog_id','=','dogs.id')
            ->whereNull('adoptions.dog_id')
            ->orderBy('dogs.id')
            ->paginate($request->limit, ['*'], 'page', $request->page);
        });

        // Jika tidak ada anjing yang ditemukan, berikan respons JSON dengan status 404
        if (!$dogs) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }

        // Memberikan respons JSON dengan data anjing
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $dogs
        ])->setStatusCode(200);
    }
}
