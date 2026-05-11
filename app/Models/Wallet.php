<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // Field apa saja yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'balance',
    ];

    /**
     * Relasi balik ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke WalletTransaction
     */
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
} 