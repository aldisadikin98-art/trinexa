<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMission extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'reward_xp',
        'is_active',
    ];

    public function userMissions()
    {
        return $this->hasMany(UserMission::class);
    }
}
