<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedSeller extends Model
{
    protected $table = 'Featured_Sellers';
    protected $fillable = [
        'name',
        'status',
        'startcount',
        'countReviews',
    ];
    public $timestamps = true;
}