<?php

namespace Woo\Ecommerce\Repositories\Caches;

use Woo\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class CustomerCacheDecorator extends CacheAbstractDecorator implements CustomerInterface
{
}
