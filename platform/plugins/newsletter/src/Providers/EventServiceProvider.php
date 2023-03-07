<?php

namespace Woo\Newsletter\Providers;

use Woo\Newsletter\Events\SubscribeNewsletterEvent;
use Woo\Newsletter\Events\UnsubscribeNewsletterEvent;
use Woo\Newsletter\Listeners\AddSubscriberToMailchimpContactListListener;
use Woo\Newsletter\Listeners\AddSubscriberToSendGridContactListListener;
use Woo\Newsletter\Listeners\RemoveSubscriberToMailchimpContactListListener;
use Woo\Newsletter\Listeners\SendEmailNotificationAboutNewSubscriberListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscribeNewsletterEvent::class => [
            SendEmailNotificationAboutNewSubscriberListener::class,
            AddSubscriberToMailchimpContactListListener::class,
            AddSubscriberToSendGridContactListListener::class,
        ],
        UnsubscribeNewsletterEvent::class => [
            RemoveSubscriberToMailchimpContactListListener::class,
        ],
    ];
}
