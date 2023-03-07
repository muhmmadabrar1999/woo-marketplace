<?php

namespace Woo\Ecommerce\Repositories\Caches;

use Woo\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnItemCacheDecorator extends CacheAbstractDecorator implements OrderReturnItemInterface
{
}
