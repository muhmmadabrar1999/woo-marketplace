<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\Assets;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\Assets
 */
class AssetsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Assets::class;
    }
}
