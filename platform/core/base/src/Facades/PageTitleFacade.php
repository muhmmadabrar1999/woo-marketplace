<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\PageTitle;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\PageTitle
 */
class PageTitleFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PageTitle::class;
    }
}
