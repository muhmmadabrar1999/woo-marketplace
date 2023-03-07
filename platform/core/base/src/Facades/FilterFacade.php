<?php

namespace Woo\Base\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\Filter
 */
class FilterFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:filter';
    }
}
