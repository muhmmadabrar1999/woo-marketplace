<?php

namespace Woo\AuditLog\Repositories\Caches;

use Woo\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

/**
 * @since 16/09/2016 10:55 AM
 */
class AuditLogCacheDecorator extends CacheAbstractDecorator implements AuditLogInterface
{
}
