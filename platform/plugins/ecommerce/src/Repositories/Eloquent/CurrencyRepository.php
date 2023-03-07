<?php

namespace Woo\Ecommerce\Repositories\Eloquent;

use Woo\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Woo\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order', 'ASC');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
