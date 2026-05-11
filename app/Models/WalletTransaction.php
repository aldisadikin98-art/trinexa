<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'type', 'amount', 'description', 'reference_id', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // Apakah transaksi ini uang masuk?
    public function getIsIncomeAttribute(): bool
    {
        return in_array($this->type, ['topup', 'reward', 'recycle', 'credit']);
    }

    // Label Bahasa Indonesia
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'topup'      => 'Top Up',
            'purchase'   => 'Belanja Naturea',
            'withdrawal' => 'Tarik Saldo',
            'reward'     => 'Hadiah & Koin',
            'credit'     => 'Pengembalian Dana',
            'recycle'    => 'Daur Ulang',
            default      => ucfirst($this->type),
        };
    }

    // Warna badge status
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'success' => 'bg-green-50 text-green-600 border-green-200',
            'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
            'failed'  => 'bg-red-50 text-red-600 border-red-200',
            default   => 'bg-gray-50 text-gray-500 border-gray-200',
        };
    }

    // Label status Bahasa Indonesia
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Berhasil',
            'pending' => 'Menunggu',
            'failed'  => 'Gagal',
            default   => ucfirst($this->status),
        };
    }
}
