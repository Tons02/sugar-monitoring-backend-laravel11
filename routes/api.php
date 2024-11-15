<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('login',[AuthController::class,'login']);

Route::group(["middleware" => ["auth:sanctum"]], function () {
Route::apiResource("user", UserController::class);
Route::put('user-archived/{id}',[UserController::class,'archived']);
});

