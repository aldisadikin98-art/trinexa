<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkinProgress extends Model
{
    use HasFactory;

    protected $table = 'user_skin_progress';

    protected $fillable = ['user_id', 'content_id', 'completed_at', 'xp_earned'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(SkinContent::class, 'content_id');
    }
}
