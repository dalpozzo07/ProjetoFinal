<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('unitPrice', 10, 2)
            ->nullable();

            $table->foreignId('order_id')
            ->constrained('orders')
            ->onDelete('cascade');
            
            $table->foreignId('product_id')
            ->constrained('products')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
