<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Cart\Cart;
use Illuminate\Support\Facades\Facade;

class CartFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Cart::class;
    }
}
