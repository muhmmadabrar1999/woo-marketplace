<?php

namespace Woo\Ecommerce\Models;

use Woo\Base\Models\BaseModel;

class GroupedProduct extends BaseModel
{
    protected $table = 'ec_grouped_products';

    protected $fillable = [
        'parent_product_id',
        'product_id',
        'fixed_qty',
    ];

    public $timestamps = false;
}
