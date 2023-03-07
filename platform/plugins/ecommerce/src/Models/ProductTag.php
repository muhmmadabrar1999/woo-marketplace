<?php

namespace Woo\Ecommerce\Models;

use Woo\Base\Casts\SafeContent;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductTag extends BaseModel
{
    protected $table = 'ec_product_tags';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'ec_product_tag_product', 'tag_id', 'product_id')
            ->where('is_variation', 0);
    }
}
