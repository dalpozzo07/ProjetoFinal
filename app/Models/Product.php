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
        'image',
    
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orderItems()
    {
        return $this->belongsTo(orderItems::class, 'product_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    } 
}
