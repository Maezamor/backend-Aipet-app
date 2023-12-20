<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ServiceCreateRequest;
use App\Http\Requests\ServiceUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceController extends Controller
{
    protected $durasi =  10;
    private $timeThershold;
    private $day = 1;
    public function get(Request $request): JsonResponse
    {
        $start = microtime(true);
        $this->timeThreshold =  now()->subDay($this->day);

         //lakukan pengechekan data apakah ada data baru
         $newOrUpdateData = Service::where('updated_at','>', $this->timeThreshold)->get();
         $deleteData = Service::where('deleted_at','>',$this->timeThreshold)->get();

         if ($newOrUpdateData->isNotEmpty() || $deleteData->isNotEmpty()) {
            Cache::forget('serviceList_ '. $request->page);
        }

        $service = Cache::remember('serviceList_ '. $request->page, now()->addMinutes($this->durasi), function () use ($request){
            return Service::paginate($request->limit, ['*'], 'page', $request->page);
       });
        
        if (!$service) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "noting"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $end = microtime(true);

        $executionTime = ($end - $start) * 1000 . 'ms'; 

        return response()->json([
            'time' =>  $executionTime,
            'error' => false,
            'message' => 'Success',
            "data" => $service
        ])->setStatusCode(200);
    }

    public function getDetail(Request $request)
    {
        if (!$request->id) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found for id"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $service = Cache::remember('serviceDetail_ '. $request->id, now()->addMinutes($this->durasi), function () use ($request){
            return Service::find($request->id);
        });
        
        if (!$service) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return response()->json([
            'data' => $service
        ])->setStatusCode(200);
    }

    public function create(ServiceCreateRequest $request): JsonResponse
    {
        $data =  $request->validated();

        $storage  =  new GoogleCloudStorageController();
        $patch =  $storage->uploadFile($request,'imageService');

        if($patch ==  400){
            return response()->json([
                'error' => true,
                'message' => 'your have not picture'
            ])->setStatusCode(400);
        } else if ($patch ==  500) {
            return response()->json([
                'error' => true,
                'message' => 'serve save file is not responding'
            ])->setStatusCode(500);
        }

        $service =  new Service($data);
        $service->picture = $patch;
        $service->save();

        return response()->json([
            'data' => $service
        ])->setStatusCode(201);
    }

    public function update( ServiceUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $service = Cache::remember('serviceUpdate_'. $request->id, now()->addMinutes($this->durasi), function () use ($request){
            return Service::find($request->id);
        });

        if (!$service) {
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
            $delete =  $storage->deleteFile($service->picture);

            if ($delete == 404){
                throw new HttpResponseException(response()->json([
                    "errors" => [
                        "message" => [
                            "not found image"
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
             $patch =  $storage->uploadFile($request, 'imageService');
             $data['picture'] = $patch;
        }else {
            //misalnya dia tidak meruba gambar selter maka diisi dengan patch gambar lama
            $data['picture'] = $service->picture;
        }

        

        
        $service->fill($data);
        $service->save();

        return response()->json([
            "data" => $service
        ])->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        $service = Service::find($request->id);

        if (!$service) {
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
        $delete =  $storage->deleteFile($service->picture);
        $service->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
