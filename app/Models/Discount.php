<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = 
    [
        'startDate',
        'description',
        'endDate',
        'discountPercentage',
        'product_id'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
