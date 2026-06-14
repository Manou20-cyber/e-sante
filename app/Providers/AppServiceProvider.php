<?php

namespace App\Providers;

use App\Services\ParametreService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::share('appSettings', ParametreService::getSettings());
    }
}
