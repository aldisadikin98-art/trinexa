<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopVoucher extends Model
{
    protected $fillable = [
        'code', 'name', 'type', 'value', 'min_purchase', 'max_discount',
        'expired_at', 'quota', 'used_count', 'is_active',
    ];

    protected $casts = [
        'value'        => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'expired_at'   => 'datetime',
        'is_active'    => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────
    public function usages()
    {
        return $this->hasMany(ShopVoucherUsage::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────
    /**
     * Hitung diskon yang diberikan untuk subtotal tertentu
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
        } else {
            $discount = $this->value;
        }
        return min($discount, $subtotal); // diskon tidak boleh melebihi subtotal
    }

    /**
     * Cek apakah voucher masih bisa dipakai
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->expired_at && $this->expired_at->isPast()) return false;
        if ($this->quota !== null && $this->used_count >= $this->quota) return false;
        return true;
    }

    /**
     * Apakah user ini sudah pernah pakai voucher ini
     */
    public function isUsedByUser(int $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->exists();
    }

    /**
     * Validasi voucher untuk user dan subtotal tertentu
     * Return: ['valid' => bool, 'message' => string, 'discount' => float]
     */
    public function validate(int $userId, float $subtotal): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Voucher tidak aktif.'];
        }
        if ($this->expired_at && $this->expired_at->isPast()) {
            return ['valid' => false, 'message' => 'Voucher sudah kadaluarsa.'];
        }
        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return ['valid' => false, 'message' => 'Kuota voucher sudah habis.'];
        }
        if ($this->isUsedByUser($userId)) {
            return ['valid' => false, 'message' => 'Voucher sudah pernah dipakai.'];
        }
        if ($subtotal < $this->min_purchase) {
            return [
                'valid'   => false,
                'message' => 'Minimum belanja Rp ' . number_format($this->min_purchase, 0, ',', '.'),
            ];
        }

        return [
            'valid'    => true,
            'message'  => 'Voucher berhasil digunakan!',
            'discount' => $this->calculateDiscount($subtotal),
        ];
    }

    /**
     * Label tipe voucher
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'percent'
            ? $this->value . '%'
            : 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}
