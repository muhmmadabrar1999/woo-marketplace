<?php

namespace Woo\Ecommerce\Repositories\Caches;

use Woo\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class FlashSaleCacheDecorator extends CacheAbstractDecorator implements FlashSaleInterface
{
    public function getAvailableFlashSales(array $with = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
