<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('cartItems', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('unitPrice', 10, 2);
            $table->foreignId('cart_id')
            ->constrained('carts')
            ->onDelete('cascade');
            $table->foreignId('product_id')
            ->constrained('products')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('cartItems');
    }
};
