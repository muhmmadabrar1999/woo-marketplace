<?php

namespace Woo\Ads\Facades;

use Woo\Ads\Supports\AdsManager;
use Illuminate\Support\Facades\Facade;

class AdsManagerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdsManager::class;
    }
}
