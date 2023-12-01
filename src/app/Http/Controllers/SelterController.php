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
    public function get(Request $request): JsonResponse
    {
        $selter =  Selter::paginate($request->limit, ['*'], 'page', $request->page);

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

    public function getDetail(Request $request): JsonResponse
    {
        $selter = Selter::find($request->id);
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

    public function update(SelterUpdateRequest $request): JsonResponse
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
        $data = $request->validated();
        $selter->fill($data);
        $selter->save();

        return response()->json([
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
        $selter->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
