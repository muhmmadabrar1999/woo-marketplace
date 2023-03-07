<?php

namespace Woo\Ecommerce\Facades;

use Woo\Ecommerce\Supports\InvoiceHelper;
use Illuminate\Support\Facades\Facade;

class InvoiceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvoiceHelper::class;
    }
}
