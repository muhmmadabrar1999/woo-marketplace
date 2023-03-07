<?php

namespace Woo\Ecommerce\Models;

use Woo\Base\Casts\SafeContent;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Base\Models\BaseModel;

class ProductLabel extends BaseModel
{
    protected $table = 'ec_product_labels';

    protected $fillable = [
        'name',
        'color',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];
}
