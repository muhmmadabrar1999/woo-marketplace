<?php

namespace Woo\Location\Facades;

use Woo\Location\Location;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Location\Location
 */
class LocationFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Location::class;
    }
}
