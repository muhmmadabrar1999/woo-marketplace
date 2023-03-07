<?php

namespace Woo\Ecommerce\Repositories\Eloquent;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Woo\Support\Repositories\Eloquent\RepositoriesAbstract;

class FlashSaleRepository extends RepositoriesAbstract implements FlashSaleInterface
{
    public function getAvailableFlashSales(array $with = [])
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->latest();

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
