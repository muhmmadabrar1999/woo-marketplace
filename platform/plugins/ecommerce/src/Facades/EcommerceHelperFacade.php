<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\EcommerceHelper;
use Illuminate\Support\Facades\Facade;

class EcommerceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EcommerceHelper::class;
    }
}
