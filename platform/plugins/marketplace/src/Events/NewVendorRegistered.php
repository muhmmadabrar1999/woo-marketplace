<?php

namespace Woo\Marketplace\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Customer;
use Illuminate\Queue\SerializesModels;

class NewVendorRegistered extends Event
{
    use SerializesModels;

    public Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}
