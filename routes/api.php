<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\DailySugarController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('login',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {

// user controller
Route::Resource("user", UserController::class)->middleware(['abilities:user:crud']);
Route::put('user-archived/{id}',[UserController::class,'archived'])->middleware(['abilities:user:create, user:update, user:delete']);

// user controller
Route::Resource("daily-sugar", DailySugarController::class)->middleware(['abilities:daily_monitoring:crud']);
Route::put('daily-sugar-archived/{id}',[DailySugarController::class,'archived'])->middleware(['abilities:daily_monitoring:crud']);

// logout 
Route::post('logout',[AuthController::class,'logout']);

});

