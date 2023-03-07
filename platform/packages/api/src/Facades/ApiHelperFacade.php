<?php

namespace Woo\Api\Facades;

use Woo\Api\Supports\ApiHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Api\Supports\ApiHelper
 */
class ApiHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ApiHelper::class;
    }
}
