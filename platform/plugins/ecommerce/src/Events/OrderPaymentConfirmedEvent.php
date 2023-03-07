<?php

namespace Woo\Ecommerce\Events;

use Woo\ACL\Models\User;
use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Order;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmedEvent extends Event
{
    use SerializesModels;

    public Order $order;

    public User $confirmedBy;

    public function __construct(Order $order, User $confirmedBy)
    {
        $this->order = $order;
        $this->confirmedBy = $confirmedBy;
    }
}
