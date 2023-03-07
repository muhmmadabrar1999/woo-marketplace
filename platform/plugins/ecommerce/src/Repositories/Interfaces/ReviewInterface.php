<?php

namespace Woo\Ecommerce\Repositories\Interfaces;

use Woo\Support\Repositories\Interfaces\RepositoryInterface;

interface ReviewInterface extends RepositoryInterface
{
    /**
     * @param int $productId
     * @return mixed
     */
    public function getGroupedByProductId(array $productId);
}
