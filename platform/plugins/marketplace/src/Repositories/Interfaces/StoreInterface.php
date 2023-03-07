<?php

namespace Woo\Marketplace\Repositories\Interfaces;

use Woo\Support\Repositories\Interfaces\RepositoryInterface;

interface StoreInterface extends RepositoryInterface
{
    public function handleCommissionEachCategory(array $data): array;

    public function getCommissionEachCategory(): array;
}
