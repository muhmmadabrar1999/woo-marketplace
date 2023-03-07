<?php

namespace Woo\Theme\Facades;

use Woo\Theme\Theme;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Theme\Theme
 */
class ThemeFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Theme::class;
    }
}
