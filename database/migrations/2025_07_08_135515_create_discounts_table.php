<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->date('startDate');
            $table->date('endDate');
            $table->decimal('discountPercentage', 5, 2);

            $table->foreignId('product_id')
            ->constrained('products')     
            ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
