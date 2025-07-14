<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    
    protected $fillable = [
        'street',
        'number',
        'zip',
        'city',
        'state',
        'country',
        'user_id',
    ];

    public function users() // pertence a um usuario
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders() // pertence a um usuario
    {
        return $this->belongsTo(Order::class, 'address_id');
    }
}
