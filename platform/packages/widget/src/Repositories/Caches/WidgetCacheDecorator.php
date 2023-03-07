<?php

namespace Woo\Widget\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Widget\Repositories\Interfaces\WidgetInterface;
use Illuminate\Database\Eloquent\Collection;

class WidgetCacheDecorator extends CacheAbstractDecorator implements WidgetInterface
{
    public function getByTheme(string $theme): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
