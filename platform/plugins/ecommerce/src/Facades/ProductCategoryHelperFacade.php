<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\ProductCategoryHelper;
use Illuminate\Support\Facades\Facade;

class ProductCategoryHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductCategoryHelper::class;
    }
}
