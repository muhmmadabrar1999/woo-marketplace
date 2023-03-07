<?php

namespace Woo\Slug\Facades;

use Woo\Slug\SlugHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Slug\SlugHelper
 */
class SlugHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SlugHelper::class;
    }
}
