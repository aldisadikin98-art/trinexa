<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserActivityCompleted::class,
            \App\Listeners\AwardLoyaltyPoints::class,
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\UpdateLoginStreak::class,
        );

        // Bagikan variabel global ke komponen navigasi untuk menghindari N+1 queries di view
        \Illuminate\Support\Facades\View::composer(
            ['components.navbar', 'layouts.navigation'], 
            function ($view) {
                $cartCount = 0;
                $unreadNotifCount = 0;
                
                if (auth()->check()) {
                    $user = auth()->user();
                    $cartCount = \App\Models\CartItem::where('user_id', $user->id)->count();
                    $unreadNotifCount = \App\Models\SiteNotification::where('user_id', $user->id)
                                        ->whereNull('read_at')
                                        ->count();
                }

                $view->with('globalCartCount', $cartCount)
                     ->with('globalUnreadNotifCount', $unreadNotifCount);
            }
        );
    }
}
