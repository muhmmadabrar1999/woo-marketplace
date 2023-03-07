<?php

namespace Woo\Theme\Facades;

use Woo\Theme\Supports\AdminBar;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Theme\Supports\AdminBar
 */
class AdminBarFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminBar::class;
    }
}
