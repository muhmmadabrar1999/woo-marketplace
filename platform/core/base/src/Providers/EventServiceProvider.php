<?php

namespace Woo\Base\Providers;

use Woo\Base\Events\AdminNotificationEvent;
use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\SendMailEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Listeners\AdminNotificationListener;
use Woo\Base\Listeners\BeforeEditContentListener;
use Woo\Base\Listeners\CreatedContentListener;
use Woo\Base\Listeners\DeletedContentListener;
use Woo\Base\Listeners\SendMailListener;
use Woo\Base\Listeners\UpdatedContentListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SendMailEvent::class => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
        AdminNotificationEvent::class => [
            AdminNotificationListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        Event::listen(['cache:cleared'], function () {
            File::delete([storage_path('cache_keys.json'), storage_path('settings.json')]);
        });
    }
}
