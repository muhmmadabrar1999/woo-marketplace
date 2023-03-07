<?php

namespace Woo\Marketplace\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;

class StoreCacheDecorator extends CacheAbstractDecorator implements StoreInterface
{
    public function handleCommissionEachCategory(array $data): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getCommissionEachCategory(): array
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
