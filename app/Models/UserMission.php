<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'daily_mission_id',
        'is_completed',
        'date',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyMission()
    {
        return $this->belongsTo(DailyMission::class);
    }
}
