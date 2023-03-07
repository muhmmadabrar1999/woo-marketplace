<?php

namespace Woo\Menu\Repositories\Interfaces;

use Woo\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface MenuNodeInterface extends RepositoryInterface
{
    public function getByMenuId(int $menuId, ?int $parentId, array $select = ['*'], array $with = ['child']): Collection;
}
