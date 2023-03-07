<?php

namespace Woo\Ecommerce\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Shipment;
use Illuminate\Queue\SerializesModels;

class ShippingStatusChanged extends Event
{
    use SerializesModels;

    public function __construct(public Shipment $shipment, public array $previousShipment = [])
    {
    }
}
