<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DiscountController;

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

    //CRUD de discontos 
    ROute::get('/discounts', [DiscountController::class, 'discount']);
    Route::post('/discounts', [DiscountController::class, 'createDiscount']);
    Route::put('/discounts/{id}', [DiscountController::class, 'updateDiscount']);
    Route::delete('/discounts/{id}', [DiscountController::class, 'deleteDiscount']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Perfil do usuário
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::delete('user/profile', [UserController::class, 'deleteProfile']);
    
    // Endereço do usuário
    Route::get('/user/address', [AddressController::class, 'address']);
    Route::post('/user/address', [AddressController::class, 'createAddress']);
    Route::put('/user/address/{id}', [AddressController::class, 'updateAddress']);
    Route::delete('/user/address/{id}', [AddressController::class, 'deleteAddress']);

    // Carrinho do usuário
    
    Route::get('/user/cart', [CartController::class, 'cart']);


});
