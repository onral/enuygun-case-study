<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Provider2Facade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'provider2';
    }
}

