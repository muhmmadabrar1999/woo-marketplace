<?php

namespace Woo\Ads\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Ads\Repositories\Interfaces\AdsInterface;
use Illuminate\Database\Eloquent\Collection;

class AdsCacheDecorator extends CacheAbstractDecorator implements AdsInterface
{
    public function getAll(): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
