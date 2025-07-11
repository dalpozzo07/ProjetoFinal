<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
        use HasApiTokens;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
       
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function addresses() // a tabela dos usuarios tem varios endereços
{
    return $this->hasMany(Address::class);
}

 public function orders() // a tabela dos usuarios tem 
{
    return $this->hasMany(Order::class);
}

public function carts() // a tabela users tem 1 cart para cada usuario.
{
    return $this->hasOne(Cart::class);
}

}
