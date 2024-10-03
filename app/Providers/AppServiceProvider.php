<?php

namespace App\Providers;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('extra', asset('extra.js')),
        ]);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
