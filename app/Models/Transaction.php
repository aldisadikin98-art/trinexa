<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'receipt_number', 'total_amount', 'status', 'payment_method',
        'midtrans_snap_token', 'midtrans_order_id',
        'shipping_address', 'courier', 'shipping_cost',
        'tracking_number', 'shipping_status',
        'shop_voucher_id', 'discount_amount', 'coins_earned', 'coins_status',
        'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'shipping_cost'   => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'cancelled_at'    => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function shopVoucher()
    {
        return $this->belongsTo(ShopVoucher::class);
    }

    public function walletTransaction()
    {
        return $this->hasOne(WalletTransaction::class, 'reference_id', 'receipt_number');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────
    /**
     * Apakah pesanan bisa dibatalkan (hanya status pending)
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Apakah bisa ditulis ulasan (status selesai)
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Label status dalam Bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Menunggu',
            'paid'       => 'Dibayar',
            'diproses'   => 'Diproses',
            'dikirim'    => 'Dikirim',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'failed'     => 'Gagal',
            default      => ucfirst($this->status),
        };
    }

    /**
     * Warna badge status (Tailwind CSS class)
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'bg-yellow-100 text-yellow-700',
            'paid'       => 'bg-blue-100 text-blue-700',
            'diproses'   => 'bg-purple-100 text-purple-700',
            'dikirim'    => 'bg-indigo-100 text-indigo-700',
            'selesai'    => 'bg-green-100 text-green-700',
            'dibatalkan' => 'bg-red-100 text-red-700',
            'failed'     => 'bg-red-100 text-red-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Format total Rupiah
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}
