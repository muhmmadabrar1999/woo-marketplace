<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\MetaBox;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\MetaBox
 */
class MetaBoxFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MetaBox::class;
    }
}
