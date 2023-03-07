<?php

namespace Woo\ACL\Providers;

use Woo\ACL\Events\RoleAssignmentEvent;
use Woo\ACL\Events\RoleUpdateEvent;
use Woo\ACL\Listeners\LoginListener;
use Woo\ACL\Listeners\RoleAssignmentListener;
use Woo\ACL\Listeners\RoleUpdateListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RoleUpdateEvent::class => [
            RoleUpdateListener::class,
        ],
        RoleAssignmentEvent::class => [
            RoleAssignmentListener::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
    ];
}
