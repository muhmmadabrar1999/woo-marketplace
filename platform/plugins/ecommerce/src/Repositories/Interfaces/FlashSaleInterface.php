<?php

namespace Woo\Ecommerce\Repositories\Interfaces;

use Woo\Support\Repositories\Interfaces\RepositoryInterface;

interface FlashSaleInterface extends RepositoryInterface
{
    /**
     * @param array $with
     * @return mixed
     */
    public function getAvailableFlashSales(array $with = []);
}
