<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'images', 'starting_price', 'current_price', 'category_id', 'start_time', 'end_time'
    ];

    public function getImagePathsAttribute()
    {
        if (!$this->image) {
            return [];
        }

        $images = explode(',', $this->image);
        return map(function($image) use ($images) {
            return Storage::disk('public')->url($image);
        });
    }

    protected function images(): Attribute
{
    return Attribute::make(
        get: fn ($value) => $value
            ? collect(explode(',', $value))->map(fn ($image) => Storage::disk('public')->url($image))->toArray()
            : []
    );
}

// public function bids(){
//     return $this->hasMany(Bid::class);
// }
}
