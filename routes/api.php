<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/register', [AuthController::class, 'register']);

Route::post('/v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/v1/logout', [AuthController::class, 'logout']);
