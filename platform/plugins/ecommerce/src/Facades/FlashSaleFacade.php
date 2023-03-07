<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\FlashSaleSupport;
use Illuminate\Support\Facades\Facade;

class FlashSaleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FlashSaleSupport::class;
    }
}
