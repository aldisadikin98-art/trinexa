<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceScanHistory extends Model
{
    /** @use HasFactory<\Database\Factories\FaceScanHistoryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'foto_url', 'result_json', 'tipe_kulit'];

    protected $casts = [
        'result_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
