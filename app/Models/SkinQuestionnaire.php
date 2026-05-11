<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkinQuestionnaire extends Model
{
    protected $fillable = ['user_id', 'answers', 'result_type', 'result_scores'];

    protected $casts = [
        'answers' => 'array',
        'result_scores' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
