<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ServiceCreateRequest;
use App\Http\Requests\ServiceUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceController extends Controller
{
    public function get(Request $request): JsonResponse
    {

        $service = Service::paginate($request->limit, ['*'], 'page', $request->page);

        if (!$service) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "noting"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return response()->json([
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

        return response()->json([
            'data' => $service
        ])->setStatusCode(200);
    }

    public function create(ServiceCreateRequest $request): JsonResponse
    {
        $data =  $request->validated();
        $service =  new Service($data);
        $service->save();

        return response()->json([
            'data' => $service
        ])->setStatusCode(201);
    }

    public function update(ServiceUpdateRequest $request): JsonResponse
    {
        if (!$request->id) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found for id"
                    ]
                ]
            ])->setStatusCode(400));
        }

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

        $data = $request->validated();

        $service->fill($data);
        $service->save();

        return response()->json([
            "data" => $service
        ])->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {
        if (!$request->id) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found for id"
                    ]
                ]
            ])->setStatusCode(400));
        }

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

        $service->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
