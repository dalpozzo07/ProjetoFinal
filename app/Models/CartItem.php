<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = 
    [
        'quantity',
        'unitPrice',
        'cart_id',
        'product_id'
    ];

  public function carts() // pertence a um usuario
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

}
