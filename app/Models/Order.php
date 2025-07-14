<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
      protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'totalAmount',
        'coupon_id',
        'totalAmount',
        'orderDate',
    ];

    
    public function addresses() // a tabela dos pedidos tem varios endereços
{
    return $this->hasMany(Address::class, 'address_id');
}

    public function coupons() // a tabela dos pedidos tem varios cupons.
{
    return $this->hasMany(Coupon::class);
}

    public function users() // pertence a um usuario
{
    return $this->belongsTo(User::class, 'user_id');
}

    public function orderItems()
{
    return $this->hasMany(OrderItems::class);
}

}
