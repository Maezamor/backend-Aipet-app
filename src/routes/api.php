<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SelterController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SterillizationController;
use App\Http\Controllers\PersonalityQuestionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

// dalam membungkases fitur yang lebih crusial,
Route::middleware(ApiAuthMiddleware::class)->group(function () {

    //User Controller
    Route::get('/users/current', [UserController::class, 'getCurrent']); // profile
    Route::patch('/users/current', [UserController::class, 'update']); // update
    Route::delete('/users/logout', [UserController::class, 'logout']); // logout


    //Api Dog Controller
    Route::post('/dogs/create', [DogController::class, 'create']);
    Route::get('/dogs', [DogController::class, 'get'])->where('id', '[0-9]+'); //
    Route::get('/dogs/filter', [DogController::class, 'filter']);
    Route::post('/dogs/search', [DogController::class, 'search']);
    Route::put('/dogs', [DogController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/dogs', [DogController::class, 'delete'])->where('id', '[0-9]+');
    Route::get('dogs/rescue', [DogController::class, 'getRescue']);

    //onboarding
    Route::get('onboarding/end', [OnboardingController::class, 'onboardingEnd']);


    // Api Adoption Controller


    // Api Selter Controller
    Route::get('/selter/list', [SelterController::class, 'get']);
    Route::get('/selter', [SelterController::class, 'getDetail'])->where('id', '[0-9]+');
    Route::post('/selter/create', [SelterController::class, 'create']);
    Route::put('/selter', [SelterController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/selter', [SelterController::class, 'delete'])->where('id', '[0-9]+');


    // Api Service Controller
    Route::get('/service/list', [ServiceController::class, 'get']);
    Route::get('/service/detail', [ServiceController::class, 'getDetail'])->where('id', '[0-9]+');
    Route::post('/service/create', [ServiceController::class, 'create']);
    Route::put('/service', [ServiceController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('service', [ServiceController::class, 'delete'])->where('id', '[0-9]+');


    //  Api mengelola tabel type
    Route::get('/admin/type/{page}/{limit}/list', [TypeController::class, 'get']);
    Route::post('/admin/type/create', [TypeController::class, 'create']);
    Route::put('/admin/type/{id}', [TypeController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/admin/type/{id}', [TypeController::class, 'delete'])->where('id', '[0-9]+');

    // Api Sterilisation Controller
    Route::get('/admin/sterils/{page}/{limit}/list', [SterillizationController::class, 'get']);
    Route::post('/admin/sterils/create', [SterillizationController::class, 'create']);
    Route::put('/admin/sterils/{id}', [SterillizationController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/admin/sterils/{id}', [SterillizationController::class, 'delete'])->where('id', '[0-9]+');

    // API Personality Controller
    Route::get('/admin/person/list', [PersonalityQuestionController::class, 'get']);
    Route::get('/admin/person/{id}', [PersonalityQuestionController::class, 'getDetail'])->where('id', '[0-9]+');
    Route::post('/admin/person/create', [PersonalityQuestionController::class, 'create']);
    Route::put('/admin/person/update/{id}', [PersonalityQuestionController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/admin/person/delete/{id}', [PersonalityQuestionController::class, 'delete'])->where('id', '[0-9]+');
});
