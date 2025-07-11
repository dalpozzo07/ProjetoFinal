<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

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
    Route::get('/users/profile', [UserController::class, 'profile'])
    ->name('profile');
    Route::put('/users/profile', [UserController::class, 'updateProfile'])
    ->name('updateProfile');
    Route::delete('users/profile', [UserController::class, 'deleteProfile'])
    ->name('deleteProfile');

});
