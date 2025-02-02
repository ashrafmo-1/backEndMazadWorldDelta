<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction_Detiales extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_name',
        'auction_description',
        'start_salary',
        'current_salary',
        'start_time',
        'end_time',
        'photo',
        'photos',
        'isFav',
        'isPublished',
    ];
}
