<?php

namespace Woo\Ecommerce\Repositories\Caches;

use Woo\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class OrderReturnCacheDecorator extends CacheAbstractDecorator implements OrderReturnInterface
{
}
