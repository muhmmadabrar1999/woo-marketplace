<?php

namespace Woo\Location\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Location\Repositories\Interfaces\CityInterface;
use Illuminate\Database\Eloquent\Collection;

class CityCacheDecorator extends CacheAbstractDecorator implements CityInterface
{
    public function filters(?string $keyword, ?int $limit = 10, array $with = [], array $select = ['cities.*']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
