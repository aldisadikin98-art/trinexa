<?php

namespace App\Listeners;

use App\Events\UserActivityCompleted;
use App\Services\LoyaltyService;

class AwardLoyaltyPoints
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    public function handle(UserActivityCompleted $event): void
    {
        $this->loyaltyService->addPoints(
            $event->user,
            $event->points,
            $event->activityType,
            $event->description
        );
    }
}
