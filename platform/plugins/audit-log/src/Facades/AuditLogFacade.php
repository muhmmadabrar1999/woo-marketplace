<?php

namespace Woo\AuditLog\Facades;

use Woo\AuditLog\AuditLog;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\AuditLog\AuditLog
 */
class AuditLogFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuditLog::class;
    }
}
