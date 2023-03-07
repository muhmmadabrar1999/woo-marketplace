<?php

namespace Woo\Menu\Facades;

use Woo\Menu\Menu;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Menu\Menu
 */
class MenuFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Menu::class;
    }
}
