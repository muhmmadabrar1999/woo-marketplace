<?php

namespace Woo\Theme\Facades;

use Woo\Theme\ThemeOption;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Theme\ThemeOption
 */
class ThemeOptionFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ThemeOption::class;
    }
}
