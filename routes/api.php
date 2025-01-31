<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RefreshTokenMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas de autenticación
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', RefreshTokenMiddleware::class])->group(function () {
    Route::post('/v1/logout', [AuthController::class, 'logout']);
});

// Rutas para el crud de usuarios
Route::middleware(['auth:sanctum', RefreshTokenMiddleware::class])->group(function () {
    Route::get('/v1/users', [UserController::class, 'index']);
    Route::get('/v1/users/{id}', [UserController::class, 'show']);
    Route::put('/v1/users/{id}', [UserController::class, 'update']);
    Route::delete('/v1/users/{id}', [UserController::class, 'destroy']);
});

// Ruta para las estadísticas
Route::get('/v1/user-statistics', [UserController::class, 'userStatistics'])->middleware(['auth:sanctum', RefreshTokenMiddleware::class]);

