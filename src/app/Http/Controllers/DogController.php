<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\Type;
use App\Models\Gender;
use App\Models\Selter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\DogResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DogUpdateRequest;
use App\Http\Requests\DogCreatedRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DogController extends Controller
{
    public function create(DogCreatedRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dog = new Dog($data);
        $dog->save();

        return (new DogResource($dog))->response()->setStatusCode(201);
    }

    public function get(int $id): JsonResponse
    {
        $dog = Dog::where('id', $id)->first();
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
        $gender =  Gender::where('id', $dog->gender_id)->first();
        $type =  Type::where('id', $dog->type_id)->first();

        return response()->json([
            'data' => [
                "dog" => $dog,
                "selter" => $selter,
                "gender" => $gender,
                "Type" => $type
            ]
        ])->setStatusCode(200);
    }

    public function update(int $id, DogUpdateRequest $request): DogResource
    {

        $dog = Dog::where('id', $id)->first();

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

    public function delete(int $id): JsonResponse
    {
        $dog = Dog::where("id", $id)->first();
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

        $results =  Dog::select('dogs.*', 'genders.gender as gender', 'types.type', 'selters.name as selter_name', 'selters.address as address')
            ->join('genders', 'dogs.gender_id', '=', 'genders.id')
            ->join('types', 'dogs.type_id', '=', 'types.id')
            ->join('selters', 'dogs.selter_id', '=', 'selters.id')
            ->orWhere('dogs.name', 'like', '%' . $query . '%')
            ->orWhere('dogs.age', 'like', '%' . $query . '%')
            ->orWhere('dogs.character', 'like', '%' . $query . '%')
            ->orWhere('genders.gender', 'like', '%' . $query . '%')
            ->orWhere('selters.name', 'like', '%' . $query . '%')
            ->orWhere('selters.address', 'like', '%' . $query . '%')
            ->get();
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
}