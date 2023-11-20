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
use App\Models\Sterlisation;
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

    public function search(int $page = 1, int $limit = 10, Request $request)
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

        $results =  Dog::select('dogs.*', 'types.type', 'types.kelompok', 'types.group', 'selters.name as selter_name', 'selters.address as address')
            ->join('sterlisations', 'dogs.steril_id', 'sterlisations.id')
            ->join('types', 'dogs.type_id', 'types.id')
            ->join('selters', 'dogs.selter_id', '=', 'selters.id')
            ->orWhere('dogs.name', 'like', '%' . $query . '%')
            ->orWhere('dogs.age', 'like', '%' . $query . '%')
            ->orWhere('dogs.character', 'like', '%' . $query . '%')
            ->orWhere('dogs.gender', 'like', '%' . $query . '%')
            ->orWhere('selters.name', 'like', '%' . $query . '%')
            ->orWhere('selters.address', 'like', '%' . $query . '%')
            ->paginate($limit, ['*'], 'page', $page);
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

    // public function getWithType(int $id = null, int $page = 1, int $limit = 10): JsonResponse
    // {
    //     $dogs = new Dog();

    //     //default page

    //     if ($id != null) {
    //         //TODO ini dilakukan jika id tidak ditemukan sehingga pada saat itu
    //         //kita akan menampilkan data default sebagai pagination
    //         $dogs = $dogs->join('types', 'dogs.type_id', '=', 'types.id')
    //             ->where('types.id', $id)->paginate($limit, ['*'], 'page', $page);
    //     } else {
    //         $dogs = $dogs->paginate($limit, ['*'], 'page', $page);
    //     }



    //     if (!$dogs) {
    //         throw new HttpResponseException(response()->json([
    //             'errors' => [
    //                 'message' => [
    //                     "not found"
    //                 ]
    //             ],
    //         ])->setStatusCode(404));
    //     }

    //     //TODO pengecheckan adata jika data yang dihasilkan tidak ada

    //     if (empty($dogs->items())) {
    //         throw new HttpResponseException(response()->json([
    //             'errors' => [
    //                 'message' => [
    //                     'not found'
    //                 ]
    //             ]
    //         ])->setStatusCode(404));
    //     }

    //     return response()->json([
    //         'data' => $dogs
    //     ])->setStatusCode(200);
    // }

    public function  filter(int $page = 1, int $limit = 10, Request $request): JsonResponse

    {
        $query =  Dog::query();

        // filter lewat jenis
        if ($request->has("type")) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('type', $request->input('type'));
            });
        }

        if ($request->has("gender")) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->has("age")) {
            $query->where('age', $request->input('age'));
        }

        if ($request->has('group')) {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('group', $request->input('group'));
            });
        }

        //default pagination


        $dogs =  $query->paginate($limit, ['*'], 'page', $page);

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
