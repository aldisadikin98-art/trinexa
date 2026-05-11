<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'title', 'slug', 'type', 'skin_type', 'content',
        'thumbnail', 'video_url', 'duration', 'views', 'is_featured', 'is_weekly_tip',
        'xp_reward', 'is_published', 'read_time'
    ];

    public function category()
    {
        return $this->belongsTo(SkinCategory::class, 'category_id');
    }

    public function progresses()
    {
        return $this->hasMany(UserSkinProgress::class, 'content_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(UserBookmark::class, 'skin_content_id');
    }
}
