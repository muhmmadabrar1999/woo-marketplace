<?php

namespace Woo\AuditLog\Providers;

use Woo\AuditLog\Events\AuditHandlerEvent;
use Woo\AuditLog\Listeners\AuditHandlerListener;
use Woo\AuditLog\Listeners\CreatedContentListener;
use Woo\AuditLog\Listeners\DeletedContentListener;
use Woo\AuditLog\Listeners\LoginListener;
use Woo\AuditLog\Listeners\UpdatedContentListener;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AuditHandlerEvent::class => [
            AuditHandlerListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
    ];
}
