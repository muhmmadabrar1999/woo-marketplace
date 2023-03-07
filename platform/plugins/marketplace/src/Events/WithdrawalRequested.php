<?php

namespace Woo\Marketplace\Events;

use Woo\Base\Events\Event;
use Woo\Ecommerce\Models\Customer;
use Woo\Marketplace\Models\Withdrawal;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequested extends Event
{
    use SerializesModels;

    public Customer $customer;

    public Withdrawal $withdrawal;

    public function __construct(Customer $customer, Withdrawal $withdrawal)
    {
        $this->customer = $customer;
        $this->withdrawal = $withdrawal;
    }
}
