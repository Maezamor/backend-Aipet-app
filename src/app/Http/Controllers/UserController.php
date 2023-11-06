<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse {
        // geting request from post
        $data = $request->validated();

       // if have already account give feedback
       if(User::where('email', $data['email'])->count() == 1){
        //giving feedback
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => [
                        "email already exist and registered"
                    ]
                ]
            ], 400));
       }

       // go to create user
       $user = new User($data);
       $user->password = Hash::make($data['password']);
       // go create
       $user->save();
       return (new UserResource($user))->response()->setStatusCode(201);
    }
}
