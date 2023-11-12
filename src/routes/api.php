<?php

use App\Http\Controllers\DogController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;

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
    Route::get('/users/current', [UserController::class, 'getCurrent']);
    Route::patch('/users/current', [UserController::class, 'update']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/dogs/create', [DogController::class, 'create']);
    Route::get('/dogs/{id}', [DogController::class, 'get'])->where('id', '[0-9]+');
    Route::post('/dogs/search', [DogController::class, 'search']);
    Route::put('/dogs/{id}', [DogController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/dogs/{id}', [DogController::class, 'delete'])->where('id', '[0-9]+');


    //  Api mengelola tabel type
    Route::get('/admin/type', [TypeController::class, 'get']);
    Route::post('/admin/type/create', [TypeController::class, 'create']);
    Route::put('/admin/type/{id}', [TypeController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/admin/type/{id}', [TypeController::class, 'delete'])->where('id', '[0-9]+');
});
