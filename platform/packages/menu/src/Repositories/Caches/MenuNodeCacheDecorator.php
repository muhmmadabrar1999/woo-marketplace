<?php

namespace Woo\Menu\Repositories\Caches;

use Woo\Menu\Repositories\Interfaces\MenuNodeInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Illuminate\Database\Eloquent\Collection;

class MenuNodeCacheDecorator extends CacheAbstractDecorator implements MenuNodeInterface
{
    public function getByMenuId(int $menuId, ?int $parentId, array $select = ['*'], array $with = ['child']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
