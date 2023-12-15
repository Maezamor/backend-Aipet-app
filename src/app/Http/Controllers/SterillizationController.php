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
    public function get(Request $request): JsonResponse
    {
        $result = Sterlisation::paginate($request->limit, ['*'], 'page', $request->page);

        return response()->json([
            'data' => $result
        ])->setStatusCode(200);
    }

    public function create(SterilizationRequest $request): JsonResponse
    {
        $data =  $request->validated();

        $steril =  Sterlisation::create([
            'name' => $request->name,

        ]);

        return response()->json([
            'data' => $steril
        ])->setStatusCode(201);
    }

    public function update( SterilizationUpdate $request): JsonResponse
    {

        $data = $request->validated();

        $steril = Sterlisation::find($request->id);

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
        ])->setStatusCode(200);
    }

    public function delete(Request $request): JsonResponse
    {

        $steril =  Sterlisation::find($request->id);

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