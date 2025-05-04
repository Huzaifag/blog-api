<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

Route::get('/v1/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/register', [AuthController::class, 'register']);

Route::post('/v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/v1/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/v1/posts', PostController::class);
    Route::resource('/v1/likes', LikeController::class);
    Route::resource('/v1/comments', CommentController::class);
});


