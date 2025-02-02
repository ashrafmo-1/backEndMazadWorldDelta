<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hero_sections extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'best_auctions',
        'categories',
        'navbar_links',
        'contact_numbers',
    ];

    protected $casts = [
        'best_auctions' => 'array',
        'categories' => 'array',
        'navbar_links' => 'array',
        'contact_numbers' => 'array',
    ];
}