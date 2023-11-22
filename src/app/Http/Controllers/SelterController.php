<?php

namespace App\Http\Controllers;

use App\Http\Requests\SelterCreateRequest;
use App\Http\Requests\SelterUpdateRequest;
use App\Models\Selter;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SelterController extends Controller
{
    public function get(int $page = 1, int $limit = 10): JsonResponse
    {
        $selter =  Selter::paginate($limit, ['*'], 'page', $page);

        if (!$selter) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "data is noting"
                    ]
                ],
            ])->setStatusCode(404));
        }

        return response()->json([
            "data" => $selter
        ])->setStatusCode(200);
    }

    public function getDetail(int $id): JsonResponse
    {
        $selter = Selter::find($id);
        if (!$selter) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'message' => [
                        "not found"
                    ]
                ],
            ])->setStatusCode(404));
        }

        return response()->json([
            "data" => $selter
        ])->setStatusCode(200);
    }

    public  function create(SelterCreateRequest $request): JsonResponse
    {

        $data = $request->validated();

        $selter = new Selter($data);
        $selter->save();



        return response()->json([
            "data" => $selter
        ])->setStatusCode(201);
    }

    public function update(int $id, SelterUpdateRequest $request): JsonResponse
    {
        if (!$id) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found for id"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $selter = Selter::find($id);
        if (!$selter) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $data = $request->validated();
        $selter->fill($data);
        $selter->save();

        return response()->json([
            "data" => $selter
        ])->setStatusCode(200);
    }

    public function delete(int $id): JsonResponse
    {
        $selter = Selter::find($id);

        if (!$selter) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $selter->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
