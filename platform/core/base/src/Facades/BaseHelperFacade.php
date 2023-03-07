<?php

namespace Woo\Base\Facades;

use Woo\Base\Helpers\BaseHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Helpers\BaseHelper
 */
class BaseHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseHelper::class;
    }
}
