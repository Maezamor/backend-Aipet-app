<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        // geting request from post
        $data = $request->validated();

        // if have already account give feedback
        if (User::where('email', $data['email'])->count() == 1) {
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
        $user->role_id = 3;
        $user->password = Hash::make($data['password']);
        // go create
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        // get data from request
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        // get check for databases
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        //Regenerate Access Token

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function getCurrent(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        //get data from request
        if ($request->header('Content-Type') !== 'application/json') {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Invalid content Type'
                    ]
                ]
            ])->setStatusCode(400));
        }
        $data = $request->validated();

        //mengambil informasi user saat ini
        $user =  Auth::user();

        if (isset($data['username'])) {
            $user->username = $data['username'];
        }
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['address'])) {
            $user->address = $data['address'];
        }
        if (isset($data['phone'])) {
            $user->phone = $data['phone'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return new UserResource($user);
    }

    //* Function untuk logout
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        $users =  User::find($user->id);
        $users->token = null;
        $users->save();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
