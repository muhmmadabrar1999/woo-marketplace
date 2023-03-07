<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\DashboardMenu;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\DashboardMenu
 */
class DashboardMenuFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DashboardMenu::class;
    }
}
