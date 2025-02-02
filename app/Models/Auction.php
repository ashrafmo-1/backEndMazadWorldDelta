<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'starting_price', 'current_price', 'user_id', 'start_time', 'end_time'
    ];
}
