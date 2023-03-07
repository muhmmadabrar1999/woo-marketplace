<?php

namespace Woo\Theme\Facades;

use Woo\Theme\Supports\SiteMapManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Theme\Supports\SiteMapManager
 */
class SiteMapManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SiteMapManager::class;
    }
}
