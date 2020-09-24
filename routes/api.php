<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Teste
Route::get('/ping', function () {
    return ['pong' => true];
});

// Rota para acessos não autorizados
Route::get('/401', [AuthController::class, 'unauthorized'])->name('401');

// Rotas de autenticação
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

// Rotas de usuários
Route::post('/users', [AuthController::class, 'create']);
Route::get('/user', [UserController::class, 'viewCurrent']);
Route::get('/user/{id}', [UserController::class, 'view']);
Route::get('/users', [UserController::class, 'list']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/delete/{id}', [UserController::class, 'delete']);

// Rotas de notas
Route::get('/note/{id}', [NoteController::class, 'view']);
Route::get('/notes', [NoteController::class, 'list']);
Route::post('/notes', [NoteController::class, 'create']);
Route::put('/notes/{id}', [NoteController::class, 'update']);
Route::post('/notes/delete/{id}', [NoteController::class, 'delete']);