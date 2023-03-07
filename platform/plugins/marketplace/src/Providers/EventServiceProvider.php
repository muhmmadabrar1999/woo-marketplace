<?php

namespace Woo\Marketplace\Providers;

use Woo\Ecommerce\Events\OrderCreated;
use Woo\Marketplace\Events\WithdrawalRequested;
use Woo\Marketplace\Listeners\AddStoreSiteMapListener;
use Woo\Marketplace\Listeners\OrderCreatedEmailNotification;
use Woo\Marketplace\Listeners\SaveVendorInformationListener;
use Woo\Marketplace\Listeners\WithdrawalRequestedNotification;
use Woo\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SaveVendorInformationListener::class,
        ],
        RenderingSiteMapEvent::class => [
            AddStoreSiteMapListener::class,
        ],
        OrderCreated::class => [
            OrderCreatedEmailNotification::class,
        ],
        WithdrawalRequested::class => [
            WithdrawalRequestedNotification::class,
        ],
    ];
}
