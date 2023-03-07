<?php

namespace Woo\Ecommerce\Repositories\Eloquent;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Woo\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends RepositoriesAbstract implements ReviewInterface
{
    public function getGroupedByProductId($productId)
    {
        $data = $this->model
            ->select([DB::raw('COUNT(star) as star_count'), 'star'])
            ->where([
                'product_id' => $productId,
                'status' => BaseStatusEnum::PUBLISHED,
            ])
            ->groupBy('star');

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
