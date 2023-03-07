<?php

namespace Woo\Ecommerce\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderCancelledEvent extends Event
{
    use SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
