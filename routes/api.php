<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/users', [AuthController::class, 'all_users']);
    Route::get('/auth/user', [AuthController::class, 'current_user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user/{id}', [AuthController::class, 'user_by_id']);
    Route::put('/auth/user/{id}', [AuthController::class, 'update_user']);
    Route::delete('/auth/user/{id}', [AuthController::class, 'delete_user']);
});
