<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteNotification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'link', 'read_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
