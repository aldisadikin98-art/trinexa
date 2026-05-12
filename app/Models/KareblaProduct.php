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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function redemptions()
    {
        return $this->hasMany(KareblaRedemption::class);
    }

    /**
     * URL gambar utama
     */
    public function getPrimaryImageAttribute(): string
    {
        if (is_array($this->images) && count($this->images) > 0) {
            $image = $this->images[0];
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return \Illuminate\Support\Facades\Storage::url($image);
        }
        
        return asset('images/logo karebla.jpeg');
    }
}
