<?php

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;
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
        // Share logo globally
        View::composer('*', function ($view): void {
            try {
                $logoSetting = GeneralSetting::latest()->first();
                $logoPath = $logoSetting?->logo_path;
                $hasLogo = $logoPath && Storage::disk('public')->exists($logoPath);
                
                $view->with('logoPath', $hasLogo ? $logoPath : null);
            } catch (\Exception $e) {
                // Handle database connection errors gracefully
                $view->with('logoPath', null);
            }
        });

        View::composer('layouts.admin', function ($view): void {
            try {
                $unreadCount = ContactMessage::whereNull('handled_at')
                    ->orWhere('status', 'new')
                    ->count();

                $view->with('adminUnreadMessagesCount', $unreadCount);
            } catch (\Exception $e) {
                // Handle database connection errors gracefully
                $view->with('adminUnreadMessagesCount', 0);
            }
        });
    }
}
