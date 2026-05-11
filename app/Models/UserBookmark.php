<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skin_content_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(SkinContent::class, 'skin_content_id');
    }
}
