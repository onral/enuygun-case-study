<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Provider1Facade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'provider1';
    }
}

