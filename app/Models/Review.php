<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'transaction_id', 'transaction_item_id',
        'rating', 'skin_type', 'body', 'status',
        'is_verified_purchase', 'helpful_count',
        'admin_reply', 'admin_replied_at',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'admin_replied_at'     => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionItem()
    {
        return $this->belongsTo(TransactionItem::class);
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function helpfuls()
    {
        return $this->hasMany(ReviewHelpful::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────
    /**
     * Apakah user ini sudah menandai ulasan ini helpful
     */
    public function isHelpfulByUser(int $userId): bool
    {
        return $this->helpfuls()->where('user_id', $userId)->exists();
    }

    /**
     * Nama reviewer yang disamarkan: "An***" dari "Anisa"
     */
    public function getMaskedNameAttribute(): string
    {
        $name = $this->user->name ?? 'User';
        if (strlen($name) <= 2) return $name . '***';
        return substr($name, 0, 2) . str_repeat('*', max(3, strlen($name) - 2));
    }

    /**
     * Stars display (1-5)
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
