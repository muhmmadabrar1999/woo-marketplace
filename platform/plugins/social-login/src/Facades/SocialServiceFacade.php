<?php

namespace Woo\SocialLogin\Facades;

use Woo\SocialLogin\Supports\SocialService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\SocialLogin\Supports\SocialService
 */
class SocialServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialService::class;
    }
}
