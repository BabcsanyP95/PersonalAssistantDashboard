<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [UserController::class, 'show']);

    Route::put('/profile', [UserController::class, 'update']);

    Route::put('/profile/password', [UserController::class, 'updatePassword']);

    Route::post('/logout', [AuthController::class, 'logout']);
});