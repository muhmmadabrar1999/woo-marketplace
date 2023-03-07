<?php

namespace Woo\Ads\Repositories\Eloquent;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Support\Repositories\Eloquent\RepositoriesAbstract;
use Woo\Ads\Repositories\Interfaces\AdsInterface;
use Illuminate\Database\Eloquent\Collection;

class AdsRepository extends RepositoriesAbstract implements AdsInterface
{
    public function getAll(): Collection
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->notExpired()
            ->with(['metadata']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
