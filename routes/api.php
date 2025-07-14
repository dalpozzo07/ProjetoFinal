<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;

Route::get('/');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// login e registro
Route::post('/login', [AuthController::class, 'login'])
->name('login');
Route::post('/register', [AuthController::class, 'register'])
->name('register');

// Categorias
Route::get('/categories', [CategoryController::class, 'category']);

// ADMIN
Route::middleware('auth:sanctum', 'admin')->group(function () {
    //registrar moderador
    Route::post('/register-moderator', [AdminController::class, 'registerModerator']);

    //CRUD de categorias
    Route::post('/categories', [CategoryController::class, 'createCategory']);
    Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);
});

Route::middleware('auth:sanctum')->group(function () {

    //perfil do usuário
    Route::get('/users/profile', [UserController::class, 'profile']);
    Route::put('/users/profile', [UserController::class, 'updateProfile']);
    Route::delete('users/profile', [UserController::class, 'deleteProfile']);
    
    //endereço do usuário
    Route::get('/users/address', [AddressController::class, 'address']);
    Route::post('/users/address', [AddressController::class, 'createAddress']);
    Route::put('/users/address/{id}', [AddressController::class, 'updateAddress']);
    Route::delete('/users/address/{id}', [AddressController::class, 'deleteAddress']);

    
});
