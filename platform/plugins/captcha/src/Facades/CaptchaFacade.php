<?php

namespace Woo\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Captcha\Captcha
 */
class CaptchaFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'captcha';
    }
}
