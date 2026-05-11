<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KareblaRedemption extends Model
{
    protected $fillable = [
        'user_id',
        'karebla_product_id',
        'receipt_number',
        'coins_used',
        'shipping_address',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(KareblaProduct::class, 'karebla_product_id');
    }
}
