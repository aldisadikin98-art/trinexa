<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $activityType;
    public $points;
    public $description;

    public function __construct(User $user, string $activityType, int $points, string $description = null)
    {
        $this->user = $user;
        $this->activityType = $activityType;
        $this->points = $points;
        $this->description = $description;
    }
}
