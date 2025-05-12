<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        // معلومات المزايد
        'name',
        'email',
        'phone',
        'bid_value',
        'payment_method',
        
        // معلومات المنتج
        'product_title',
        'product_image_url',
        'product_price',
        
        // الحقول العامة
        'is_read'
    ];
}