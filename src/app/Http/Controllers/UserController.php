<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SaveToken;
use App\Models\Onboarding;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Controllers\GoogleCloudStorageController;

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
    
        //get check about onboarding 
        $accessOnboarding = Onboarding::where('user_id',$user->id)->exists();
        if($accessOnboarding){
            $user->status_onbr =  $accessOnboarding;
            SaveToken::create([
                'token' => $user->token,
                'user_id' => $user->id
            ]); 
            return new UserResource($user);
        }
    
        //memasukan data user ke session
        $user->status_onbr = false;

        // save token in database
        SaveToken::create([
            'token' => $user->token,
            'user_id' => $user->id
        ]);
        return new UserResource($user);
    }
    
    public function getToken(Request $request)
    {
        $token = SaveToken::latest()->first();
        $code_token =  $token->token;
        $code_user = $token->user_id;
        $token->delete();
        if (!$token) {
            return response()->json([
                'error' => true,
                'message' => 'Token tidak ditemukan'
            ])->setStatusCode(404);
        }
    
        return response()->json([
            'data' => [
                'token' => $code_token,
                'user_id' => $code_user,
            ]
        ])->setStatusCode(200);
    }
    

    
    public function getCurrent(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        //get data from request
        
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

        if (isset($data['picture'])) {

            if($user->picture == null){
                $storage  =  new GoogleCloudStorageController();
                $patch =  $storage->uploadFile($request,'imageUser');

                if($patch ==  400){
                    return response()->json([
                        'error' => true,
                        'message' => 'your have not picture'
                    ])->setStatusCode(400);
                } else if ($patch ==  500) {
                    return response()->json([
                        'error' => true,
                        'message' => 'serve save file is not responding'
                    ])->setStatusCode(500);
                }

                $user->picture = $patch;
            }else{

                $delete =  $storage->deleteFile($service->picture);

                if ($delete == 404){
                    throw new HttpResponseException(response()->json([
                        "errors" => [
                            "message" => [
                                "not found"
                            ]
                        ]
                    ])->setStatusCode(404));
                } else if ($delete ==  500){
                    throw new HttpResponseException(response()->json([
                        "errors" => [
                            "message" => [
                                "Server Storage not responding"
                            ]
                        ]
                    ])->setStatusCode(500));
                }
    
                 // melakuakn uploud kemabali image jika ada perubahan
                 $patch =  $storage->uploadFile($request, 'imageUser');
                 $user->picture = $patch;
            }

            


           
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
