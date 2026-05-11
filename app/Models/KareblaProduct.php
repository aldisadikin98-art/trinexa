<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KareblaProduct extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'collection',
        'description',
        'specs',
        'coin_price',
        'stock',
        'badge',
        'images',
        'is_active',
    ];

    protected $casts = [
        'specs' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function redemptions()
    {
        return $this->hasMany(KareblaRedemption::class);
    }
}
