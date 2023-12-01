<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Dog;
use App\Models\Type;
use App\Models\Gender;
use App\Models\Selter;
use UnableToWriteFile;
use Illuminate\Support\Str;
use App\Models\Sterlisation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\DogResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DogUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DogCreatedRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Exceptions\HttpResponseException;

class DogController extends Controller
{


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

        $selter =  Selter::where('id', $dog->selter_id)->first();
        $steril =  Sterlisation::where('id', $dog->steril_id)->first();
        $type =  Type::where('id', $dog->type_id)->first();

        return response()->json([
            'data' => [
                "dog" => $dog,
                "selter" => $selter,
                "gender" => $steril,
                "Type" => $type
            ]
        ])->setStatusCode(200);
    }

    public function update(DogUpdateRequest $request): DogResource
    {
        $data =  $request->validated();
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
        $data = $request->validated();
        $dog->fill($data);
        $dog->save();

        return new DogResource($dog);
    }

    public function delete(Request $request): JsonResponse
    {
        $dog = Dog::where("id", $request->id)->first();
        if (!$dog) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $dog->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function search(Request $request)
    {
        // melkukan validasi jadi inputan search nanti harus berupa string
        $validate =  Validator::make($request->all(), [
            'query' => 'string',
        ]);

        if ($validate->fails()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validate->errors(),
            ])->setStatusCode(400));
        }

        $query = $request->input('query');

        $results =  Dog::select('dogs.*', 'types.type', 'types.size', 'types.activity_level', 'types.groups', 'selters.name as selter_name', 'selters.address as address')
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
        if (!$results) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }

        return response()->json([
            "data" => $results
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
        // get rescue randowm for dog
        $rescue = Dog::select('id', 'rescue_story')->where('reads', false)->inRandomOrder()->first();

        // ipdate label for false to true
        $checkUpdate =  $this->updateLabel($rescue);

        return response()->json([
            'data' => $rescue
        ])->setStatusCode(200);
    }



    public function  filter(Request $request): JsonResponse

    {
        $query =  Dog::query();

        // filter lewat jenis
        if ($request->has("groups")) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('groups', $request->input('groups'));
            });
        }

        if ($request->has("sterlisasi")) {
            $query->whereHas('sterlisation', function ($q) use ($request) {
                $q->where('name', $request->input('sterlisai'));
            });
        }

        if ($request->has("age")) {
            $query->where('age', $request->input('age'));
        }

        if ($request->has('group')) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('group', $request->input('group'));
            });
        }



        $dogs =  $query->paginate($request->limit, ['*'], 'page', $request->page);


        if (!$dogs) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }

        return response()->json([
            'data' => $dogs
        ])->setStatusCode(200);
    }
}
