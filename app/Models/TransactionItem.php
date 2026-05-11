<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'quantity', 'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
