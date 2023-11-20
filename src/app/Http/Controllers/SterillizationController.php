<?php

namespace App\Http\Controllers;

use App\Http\Requests\SterilizationRequest;
use App\Http\Requests\SterilizationUpdate;
use App\Models\Sterlisation;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class SterillizationController extends Controller
{
    public function get(int $page, int $limit): JsonResponse
    {
        $result = Sterlisation::paginate($page, ['*'], 'page', $limit);

        if ($result->isEmpty()) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'you not have data'
                    ]
                ]
            ])->setStatusCode(404));
        }

        return response()->json([
            'data' => $result
        ])->setStatusCode(200);
    }

    public function create(SterilizationRequest $request): JsonResponse
    {
        $data =  $request->validated();

        $steril =  Sterlisation::create([
            'name' => $request->name
        ]);

        return response()->json([
            'data' => $steril
        ])->setStatusCode(201);
    }

    public function update(int $id, SterilizationUpdate $request): JsonResponse
    {
        if (!$id) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'id is required'
                    ]
                ]
            ])->setStatusCode(400));
        }
        $data = $request->validated();

        $steril = Sterlisation::find($data->id);

        if (!$steril) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $steril->update([
            'name' =>  $request->name
        ]);

        return response()->json([
            'data' => $steril
        ])->statusCode(200);
    }

    public function delete(int $id): JsonResponse
    {
        if (!$id) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'id is required'
                    ]
                ]
            ])->setStatusCode(400));
        }

        $steril =  Sterlisation::find($id);

        if (!$steril) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ])->setStatusCode(404));
        }

        $steril->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
