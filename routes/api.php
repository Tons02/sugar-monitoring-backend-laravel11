<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DailySugarController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('login',[AuthController::class,'login']);

Route::group(["middleware" => ["auth:sanctum"]], function () {

// user controller
Route::apiResource("user", UserController::class);
Route::put('user-archived/{id}',[UserController::class,'archived']);

// user controller
Route::apiResource("daily-sugar", DailySugarController::class);
Route::put('daily-sugar-archived/{id}',[DailySugarController::class,'archived']);

// logout 
Route::post('logout',[AuthController::class,'logout']);

});

