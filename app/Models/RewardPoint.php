<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPoint extends Model
{
    protected $fillable = ['user_id', 'points', 'type', 'description', 'reference_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
