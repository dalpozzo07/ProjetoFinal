<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('orderDate');
            $table->enum('status', ['PENDING', 'PROCESSING', 'SHIPPED', 'COMPLETED', 'CANCELED']);
            $table->decimal('totalAmount',10,2);
            
            $table->foreignId('coupon_id')
            ->nullable()
            ->constrained('coupons')
            ->onDelete('cascade');
    
            $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->foreignId('address_id')
            ->constrained('addresses')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
