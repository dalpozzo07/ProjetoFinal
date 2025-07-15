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
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CartItemController;

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

// Produtos
Route::get('/products', [ProductsController::class, 'products']);

// ADMIN
Route::middleware('auth:sanctum', 'admin')->group(function () {
    //registrar moderador
    Route::post('/register-moderator', [AdminController::class, 'registerModerator']);

    //CRUD de categorias
    Route::post('/categories', [CategoryController::class, 'createCategory']);
    Route::put('/categories/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/categories/{id}', [CategoryController::class, 'deleteCategory']);

    //CRUD de descontos 
    ROute::get('/discounts', [DiscountController::class, 'discount']);
    Route::post('/discounts', [DiscountController::class, 'createDiscount']);
    Route::put('/discounts/{id}', [DiscountController::class, 'updateDiscount']);
    Route::delete('/discounts/{id}', [DiscountController::class, 'deleteDiscount']);

    //CRUD de cupons
    Route::get('/coupons', [CouponsController::class, 'cupons']);
    Route::post('/coupons', [CouponsController::class, 'createCupons']);
    Route::put('/coupons/{id}', [CouponsController::class, 'updateCupons']);
    Route::delete('/coupons/{id}', [CouponsController::class, 'deleteCupons']);
});

// Rotas de CRUD dos produtos
Route::middleware(['auth:sanctum', 'mod'])->group(function () {
    Route::post('/products', [ProductsController::class, 'createProduct']);
    Route::put('/products/{id}', [ProductsController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductsController::class, 'deleteProduct']);
});


// Usuario autenticado
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

    // CRUD de carrinho
    Route::post('/user/cart/items', [CartItemController::class, 'addItemCart']);
    Route::get('/user/cart/items', [CartItemController::class, 'cartItem']);
    Route::delete('/user/cart/items/{id}', [CartItemController::class, 'deleteItemCart']);
    Route::put('/user/cart/items/{id}', [CartItemController::class, 'updateItemCart']);
    Route::delete('/user/cart/items', [CartItemController::class, 'cleanCartItems']);
});
