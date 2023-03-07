<?php

namespace Woo\Theme\Facades;

use Woo\Theme\Manager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Theme\Manager
 */
class ManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Manager::class;
    }
}
