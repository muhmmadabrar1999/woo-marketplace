<?php

namespace Woo\Setting\Facades;

use Woo\Setting\Supports\SettingStore;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Setting\Supports\SettingStore
 */
class SettingFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingStore::class;
    }
}
