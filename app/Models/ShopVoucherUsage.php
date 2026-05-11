<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopVoucherUsage extends Model
{
    protected $fillable = [
        'shop_voucher_id', 'user_id', 'transaction_id',
    ];

    public function shopVoucher()
    {
        return $this->belongsTo(ShopVoucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
