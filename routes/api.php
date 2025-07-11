<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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
