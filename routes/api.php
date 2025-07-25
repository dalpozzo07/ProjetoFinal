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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ModeratorController;

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
Route::get('/categories/{id}', [CategoryController::class, 'getCategory']);

// Produtos
Route::get('/products', [ProductsController::class, 'products']);
Route::get('/products/{id}', [ProductsController::class, 'getProduct']);
Route::get('/products/category/{id}', [ProductsController::class, 'getProductsByCategory']);
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
    Route::get('/discounts/{id}', [DiscountController::class, 'getDiscount']);

    //CRUD de cupons
    Route::get('/coupons', [CouponsController::class, 'cupons']);
    Route::post('/coupons', [CouponsController::class, 'createCupons']);
    Route::put('/coupons/{id}', [CouponsController::class, 'updateCupons']);
    Route::delete('/coupons/{id}', [CouponsController::class, 'deleteCupons']);
    Route::get('/coupons/{id}', [CouponsController::class, 'getCupons']);

    // CRUD de produtos 

    
});

// Rotas de CRUD dos produtos
Route::middleware(['auth:sanctum', 'mod'])->group(function () {
    Route::post('/products', [ProductsController::class, 'createProduct']);
    Route::put('/products/{id}', [ProductsController::class, 'updateProduct']);
    Route::delete('/products/{id}', [ProductsController::class, 'deleteProduct']);
    Route::put('/products/{product_id}/stock', [ProductsController::class, 'updateStock']);

    // Atualizar o status de um pedido

    Route::put('/users/orders/{id}', [ModeratorController::class, 'updateOrderStatus']);
});


// Usuario autenticado
Route::middleware('auth:sanctum')->group(function () {

    // Perfil do usuário
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::delete('user/profile', [UserController::class, 'deleteProfile']);
    
    // Endereço do usuário
    Route::get('/address', [AddressController::class, 'address']);
    Route::post('/address', [AddressController::class, 'createAddress']);
    Route::put('/address/{address_id}', [AddressController::class, 'updateAddress']);
    Route::delete('/address/{address_id}', [AddressController::class, 'deleteAddress']);
    Route::get('/address/{address_id}', [AddressController::class, 'getAddress']);

    // Carrinho do usuário
    
    Route::get('/user/cart', [CartController::class, 'cart']);

    // CRUD de carrinho
    Route::post('/user/cart/items', [CartItemController::class, 'addItemCart']);
    Route::get('/user/cart/items', [CartItemController::class, 'cartItem']);
    Route::delete('/user/cart/items/{id}', [CartItemController::class, 'deleteItemCart']);
    Route::put('/user/cart/items/{id}', [CartItemController::class, 'updateItemCart']);
    Route::delete('/user/cart/items', [CartItemController::class, 'cleanCartItems']);

    // Pedidos
    Route::get('/user/orders', [OrderController::class, 'order']);
    Route::post('/user/orders', [OrderController::class, 'createOrder']);
    Route::put('/user/orders/{id}', [OrderController::class, 'cancelOrder']);
    Route::get('/user/orders/{id}', [OrderController::class, 'getOrder']);
});
