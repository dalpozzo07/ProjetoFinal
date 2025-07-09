<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = 
    [
        'category_id',
        'name',
        'stock',
        'price',
        'discount_id',
    ];

    protected function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function orderItems()
    {
        return $this->belongsTo(orderItems::class, 'product_id');
    }

    protected function discounts()
    {
        return $this->hasMany(Discount::class, 'discount_id');
    }
}
