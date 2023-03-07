<?php

namespace Woo\Language\Facades;

use Woo\Language\LanguageManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Language\LanguageManager
 */
class LanguageFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LanguageManager::class;
    }
}
