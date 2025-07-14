<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     protected $table = 'carts';

    protected $fillable = 
    [
        'user_id',
    ];

    public function users() // pertence a um usuario
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
     public function cartItems()
{
    return $this->hasMany(CartItem::class, 'cart_id');
}

}
