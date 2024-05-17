<?php

namespace App\Providers;

use App\Contracts\AlbumServiceInterface;
use App\Services\AlbumService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AlbumServiceInterface::class,
            AlbumService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
