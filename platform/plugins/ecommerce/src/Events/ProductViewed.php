<?php

namespace Woo\Ecommerce\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Product;
use Carbon\CarbonInterface;
use Illuminate\Queue\SerializesModels;

class ProductViewed extends Event
{
    use SerializesModels;

    public function __construct(public Product $product, public CarbonInterface $dateTime)
    {
    }
}
