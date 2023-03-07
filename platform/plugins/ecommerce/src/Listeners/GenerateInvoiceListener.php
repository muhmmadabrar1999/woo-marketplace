<?php

namespace Woo\Ecommerce\Listeners;

use Woo\Ecommerce\Events\OrderCreated;
use Woo\Ecommerce\Events\OrderPlacedEvent;
use InvoiceHelper;

class GenerateInvoiceListener
{
    public function handle(OrderPlacedEvent|OrderCreated $event): void
    {
        $order = $event->order;

        InvoiceHelper::store($order);
    }
}
