<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Provider2Service;

class Provider2ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('provider2', function ($app) {
            return new Provider2Service();
        });
    }
}
