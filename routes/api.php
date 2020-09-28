<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

// Teste
Route::get('/ping', function () {
    return ['pong' => true];
});

// Rota para acessos não autorizados
Route::get('/401', [LoginController::class, 'unauthorized'])->name('401');

// Rotas de autenticação
Route::post('/auth/login', [LoginController::class, 'login']);
Route::post('/auth/logout', [LoginController::class, 'logout']);
Route::post('/auth/refresh', [LoginController::class, 'refresh']);

// Rotas de usuários
Route::get('/users', [UserController::class, 'list']);
Route::get('/user', [UserController::class, 'viewCurrent']);
Route::get('/user/{id}', [UserController::class, 'view']);
Route::post('/user', [UserController::class, 'create']);
Route::put('/user', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'delete']);

// Rotas de notas
Route::get('/notes', [NoteController::class, 'list']);
Route::get('/note/{id}', [NoteController::class, 'view']);
Route::post('/note', [NoteController::class, 'create']);
Route::put('/note/{id}', [NoteController::class, 'update']);
Route::delete('/note/{id}', [NoteController::class, 'delete']);