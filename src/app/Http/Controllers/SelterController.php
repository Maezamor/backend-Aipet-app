<?php

namespace App\Http\Controllers;

use App\Models\Selter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SelterCreateRequest;
use App\Http\Requests\SelterUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SelterController extends Controller
{

    //penetuan durasi cache

    protected $durasi = 10;
    private  $timeThreshold ;
    private $day = 1;


    public function getCoordinat (): JsonResponse
    {
        $start = microtime(true);
        $this->timeThreshold =  now()->subDay($this->day);

        $newOrUpdateData = Selter::where('updated_at','>', $this->timeThreshold)->get();
        $deleteData = Selter::where('deleted_at','>',$this->timeThreshold)->get();

        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('selterCoordinat_');
        }

        $CoordinatSelter = Cache::remember('selterCoordinat_', now()->addMinutes($this->durasi), function (){
            return Selter::select('id','name','lon','let')->get();
        });

        if (!$CoordinatSelter) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "data is noting"
                    ]
                ],
            ])->setStatusCode(404));
        }

        $end = microtime(true);

        $executionTime = ($end - $start) * 1000 . 'ms'; 
        return response()->json([
            'time' =>  $executionTime,
            'error' =>  false,
            'message' => 'success',
            "data" => $CoordinatSelter
        ])->setStatusCode(200);

    }

    public function get(Request $request): JsonResponse
    {
        $start = microtime(true);
        $this->timeThreshold =  now()->subDay($this->day);

        $newOrUpdateData = Selter::where('updated_at','>',$this->timeThreshold)->get();
        $deleteData = Selter::where('deleted_at','>',$this->timeThreshold)->get();

        if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('selterList_ '. $request->page);
        }

        $selter =  Cache::remember('selterList_ '. $request->page, now()->addMinutes($this->durasi), function () use ($request){
             return Selter::paginate($request->limit, ['*'], 'page', $request->page);
        });
        if (!$selter) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "data is noting"
                    ]
                ],
            ])->setStatusCode(404));
        }

        $end = microtime(true);

        $executionTime = ($end - $start) * 1000 . 'ms'; 
        return response()->json([
            'time' =>  $executionTime,
            'error' =>  false,
            'message' => 'success',
            "data" => $selter
        ])->setStatusCode(200);
    }

    public function getDetail(Request $request): JsonResponse
    {
        $start = microtime(true);
        $selter = Cache::remember('selterDetail_' . $request->id, now()->addMinutes($this->durasi), function () use ($request) {
            return Selter::where('id', $request->id)->first();
        });
        if (!$selter) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }
        $end = microtime(true);

        $executionTime = ($end - $start) * 1000 . 'ms'; 
        return response()->json([
            'time' =>  $executionTime,
            'error' =>  false,
            'message' => 'success',
            "data" => $selter
        ])->setStatusCode(200);
    }

    public  function create(SelterCreateRequest $request): JsonResponse
    {

        $data = $request->validated();
        $storage = new GoogleCloudStorageController();
        $patch =  $storage->uploadFile($request, 'imageSelter');

        if($patch ==  400){
            return response()->json([
                'error' => true,
                'message' => 'your have not picture'
            ])->setStatusCode(400);
        } else if ($patch ==  500) {
            return response()->json([
                'error' => true,
                'message' => 'save file is not responding'
            ])->setStatusCode(500);
        }

        $selter = new Selter($data);
        $selter->picture =  $patch;
        $selter->save();


        return response()->json([
            'error' =>  false,
            'message' => 'Success',
            "data" => $selter
        ])->setStatusCode(201);
    }

    public function update(SelterUpdateRequest $request): JsonResponse
    {
        $start =  microtime(true);
        $data = $request->validated();
   
       
        if (!$request->id) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found for id"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $selter = Cache::remember('selterUpdate_' . $request->id, now()->addMinutes($this->durasi), function () use ($request) {
            return Selter::find($request->id);
        });

        
        
       
        if (!$selter) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        if($request->hasFile('picture')){
            //medelet gambar yang ada di google cloud storage 
            $storage =  new GoogleCloudStorageController();
            $delete =  $storage->deleteFile($selter['picture']);

            if ($delete == 404){
                throw new HttpResponseException(response()->json([
                    "errors" => [
                        "message" => [
                            "not found for image"
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
             $patch =  $storage->uploadFile($request, 'imageSelter');
             $data['picture'] = $patch;
        }else {
            //misalnya dia tidak meruba gambar selter maka diisi dengan patch gambar lama
            $data['picture'] = $selter->picture;
        }
        
        $selter->fill($data);
        $selter->save();

        $end =  microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms'; 
        return response()->json([
            'time' => $executionTime,
            'error' => false,
            'message' => 'Success',
            "data" => $selter
        ])->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        $selter = Selter::find($request->id);

        if (!$selter) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        // mendelete file
        $storage =  new GoogleCloudStorageController();
        $delete =  $storage->deleteFile($selter->picture);
       // soft delete dari databases
        $selter->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
