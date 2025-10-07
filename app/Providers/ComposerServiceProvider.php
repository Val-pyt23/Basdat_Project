<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bagikan data notifikasi ke layout admin dan user
        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            // Gunakan Auth::check() untuk memastikan user sudah login
            if (Auth::check()) {
                $notifications = Auth::user()->unreadNotifications;
                $view->with('notifications', $notifications);
            } else {
                // REVISI: Jika belum login, kirim collection kosong agar tidak error
                $view->with('notifications', collect());
            }
        });
    }
}