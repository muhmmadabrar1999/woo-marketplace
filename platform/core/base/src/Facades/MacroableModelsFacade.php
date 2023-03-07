<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\MacroableModels;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\MacroableModels
 */
class MacroableModelsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MacroableModels::class;
    }
}
