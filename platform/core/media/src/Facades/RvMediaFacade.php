<?php

namespace Woo\Media\Facades;

use Woo\Media\RvMedia;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Media\RvMedia
 */
class RvMediaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RvMedia::class;
    }
}
