<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\OrderHelper;
use Illuminate\Support\Facades\Facade;

class OrderHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OrderHelper::class;
    }
}
