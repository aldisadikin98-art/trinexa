<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaceScanResult extends Model
{
    protected $fillable = [
        'user_id', 'photo_path', 'skin_type', 'skin_score',
        'score_label', 'conditions', 'good_ingredients',
        'bad_ingredients', 'morning_routine', 'night_routine',
        'tips', 'summary', 'recommended_product_ids',
    ];

    protected $casts = [
        'conditions'              => 'array',
        'good_ingredients'        => 'array',
        'bad_ingredients'         => 'array',
        'morning_routine'         => 'array',
        'night_routine'           => 'array',
        'tips'                    => 'array',
        'recommended_product_ids' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recommendedProducts()
    {
        return Product::whereIn('id', $this->recommended_product_ids ?? [])->get();
    }
}
