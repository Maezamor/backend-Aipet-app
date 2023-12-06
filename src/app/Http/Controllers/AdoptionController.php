<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use App\Models\User;
use App\Models\Adoption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdoptionController extends Controller
{
    protected $durasi = 10;
    public function getAdoption(Request $request)
    {
        $start =  microtime(true);
        $cacheKey = 'adoption_user_' . $request->user_id;

        // Coba untuk mendapatkan data dari cache
        $result = Cache::remember($cacheKey, now()->addMinutes($this->durasi), function () use ($request) {
            return Adoption::select("dogs.*", "selters.*", "users.name as adopter")
                ->join("users", "adoptions.user_id", "=", "users.id")
                ->join("dogs", "adoptions.dog_id", "=", "dogs.id")
                ->join("selters", "dogs.selter_id", "=", "selters.id")
                ->where("adoptions.user_id", $request->user_id)
                ->paginate($request->limit, ['*'], 'page', $request->page);
        });

        // Periksa apakah data ditemukan atau tidak
        if ($result->isEmpty()) {
            return response()->json([
                'error' => [
                    'message' => 'not found'
                ]
            ])->setStatusCode(404);
        }
        $end = microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms'; // Konversi ke milidetik
        return response()->json([
            'time' => $executionTime,
            'data' => $result
        ])->setStatusCode(200);
    }

    public function checkout(Request $request): JsonResponse
    {
        $start = microtime(true);
        $request->validate([
            'dog_id' => 'required'
        ]);

        $user = Auth::user();

        $dog_id = Dog::select('id')->where('id', $request->dog_id)->first();

        $adopt =  Adoption::create([
            'user_id' => $user->id,
            'dog_id' => $dog_id->id,
        ]);
        $end = microtime(true);
        $executionTime = ($end - $start) * 1000 . 'ms';
        return response()->json([
            'time' => $executionTime,
            'data' => $adopt
        ])->setStatusCode(201);
    }
}
