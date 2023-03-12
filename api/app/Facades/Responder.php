<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Responder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Utils\Responder::class;
    }
}
