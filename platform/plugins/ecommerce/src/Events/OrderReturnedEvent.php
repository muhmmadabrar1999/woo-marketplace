<?php

namespace Woo\Ecommerce\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\OrderReturn;
use Illuminate\Queue\SerializesModels;

class OrderReturnedEvent extends Event
{
    use SerializesModels;

    public OrderReturn $order;

    public function __construct(OrderReturn $order)
    {
        $this->order = $order;
    }
}
