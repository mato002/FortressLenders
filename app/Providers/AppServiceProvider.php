<?php

namespace App\Providers;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.admin', function ($view): void {
            $unreadCount = ContactMessage::whereNull('handled_at')
                ->orWhere('status', 'new')
                ->count();

            $view->with('adminUnreadMessagesCount', $unreadCount);
        });
    }
}
