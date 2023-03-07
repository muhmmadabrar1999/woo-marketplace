<?php

namespace Woo\Ecommerce\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Product;
use Illuminate\Queue\SerializesModels;

class ProductQuantityUpdatedEvent extends Event
{
    use SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
