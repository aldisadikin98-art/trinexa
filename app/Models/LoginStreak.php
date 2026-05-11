<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'highest_streak',
        'last_login_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
