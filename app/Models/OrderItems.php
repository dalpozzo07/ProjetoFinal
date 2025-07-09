<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    
    protected $fillable = [
        'quantity',
        'unitPrice',
        'order_id',
        'product_id',

    ];

    protected function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
