<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\DiscountSupport;
use Illuminate\Support\Facades\Facade;

class DiscountFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DiscountSupport::class;
    }
}
