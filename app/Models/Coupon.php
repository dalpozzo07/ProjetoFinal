<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = 
    [
        'code',
        'startDate',
        'endDate',
        'discountPercentage'
    ];
    
    protected function orders() 
    {
        return $this->belongsTo(Order::class, 'coupon_id');
    }
}
