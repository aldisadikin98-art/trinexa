<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'stock', 'type', 'image_url', 'images',
        'brand', 'category', 'is_bundle', 'bundle_discount', 'reward_points',
        'ingredients', 'skin_type', 'skin_type_not_suitable',
        'usage_instructions', 'benefits', 'bpom_number', 'is_active', 'coin_price',
    ];

    protected $casts = [
        'price'           => 'decimal:2',
        'bundle_discount' => 'decimal:2',
        'is_bundle'       => 'boolean',
        'is_active'       => 'boolean',
        'images'          => 'array',
        'ingredients'     => 'array',
        'skin_type'       => 'array',
    ];

    // ─── Boot: Auto-generate slug ─────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                // Ensure uniqueness
                $count = static::whereRaw("slug RLIKE '^{$product->slug}(-[0-9]+)?$'")->count();
                if ($count > 0) {
                    $product->slug = "{$product->slug}-{$count}";
                }
            }
        });
    }

    // ─── Scopes ───────────────────────────────────────────────────
    public function scopeNaturea($query)
    {
        return $query->where('brand', 'naturea');
    }

    public function scopeKarebla($query)
    {
        return $query->where('brand', 'karebla');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ─── Relationships ────────────────────────────────────────────
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function savingGoals()
    {
        return $this->hasMany(SavingGoal::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // ─── Computed Attributes ──────────────────────────────────────
    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_bundle && $this->bundle_discount > 0) {
            return $this->price * (1 - $this->bundle_discount / 100);
        }
        return (float) $this->price;
    }

    /**
     * Estimasi koin yang didapat saat membeli produk ini
     * Koin = floor(harga / 10000)
     */
    public function getEstimatedCoinsAttribute(): int
    {
        return (int) floor($this->effective_price / 10000);
    }

    /**
     * URL gambar utama (prioritas: images[0], lalu image_url)
     */
    public function getPrimaryImageAttribute(): string
    {
        if (!empty($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return $this->image_url ?? 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&q=80';
    }

    /**
     * Rating rata-rata produk
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Format harga Rupiah
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedEffectivePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }
}
