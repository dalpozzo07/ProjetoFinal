<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

Route::get('/');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])
->name('login');

Route::post('/register', [AuthController::class, 'register'])
->name('register');

Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::post('/register-moderator', [AdminController::class, 'registerModerator'])
    ->name('register-moderator');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users/profile', [UserController::class, 'profile']);

    Route::put('/users/profile', [UserController::class, 'updateProfile']);

    Route::delete('users/profile', [UserController::class, 'deleteProfile']);
    
    Route::get('/users/address', [AddressController::class, 'address']);
    
    Route::post('/users/createAddress', [AddressController::class, 'createAddress']);

    Route::put('/users/updateAddress/{id}', [AddressController::class, 'updateAddress']);

    Route::delete('/users/deleteAddress/{id}', [AddressController::class, 'deleteAddress']);
});
