<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\LoyaltyService;

class UpdateLoginStreak
{
    protected $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    public function handle(Login $event): void
    {
        $this->loyaltyService->updateLoginStreak($event->user);
    }
}
