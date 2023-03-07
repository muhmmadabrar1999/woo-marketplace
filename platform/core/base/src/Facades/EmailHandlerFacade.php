<?php

namespace Woo\Base\Facades;

use Woo\Base\Supports\EmailHandler;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Base\Supports\EmailHandler
 */
class EmailHandlerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EmailHandler::class;
    }
}
