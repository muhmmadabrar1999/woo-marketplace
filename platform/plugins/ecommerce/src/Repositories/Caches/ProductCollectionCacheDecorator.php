<?php

namespace Woo\Ecommerce\Repositories\Caches;

use Woo\Ecommerce\Repositories\Interfaces\ProductCollectionInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCollectionCacheDecorator extends CacheAbstractDecorator implements ProductCollectionInterface
{
    public function createSlug($name, $id)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }
}
