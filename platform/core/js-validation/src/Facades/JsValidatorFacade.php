<?php

namespace Woo\JsValidation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\JsValidation\JsValidatorFactory
 */
class JsValidatorFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-validator';
    }
}
