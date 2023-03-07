<?php

namespace Woo\Ads\Repositories\Interfaces;

use Woo\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AdsInterface extends RepositoryInterface
{
    public function getAll(): Collection;
}
