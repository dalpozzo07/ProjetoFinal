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

    
}
