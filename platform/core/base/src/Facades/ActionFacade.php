<?php

namespace Woo\Base\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\Action
 */
class ActionFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'core:action';
    }
}
