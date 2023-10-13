<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Provider1Service;

class Provider1ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('provider1', function ($app) {
            return new Provider1Service();
        });
    }
}
