<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\BreadcrumbsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\BreadcrumbsManager
 */
class BreadcrumbsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsManager::class;
    }
}
