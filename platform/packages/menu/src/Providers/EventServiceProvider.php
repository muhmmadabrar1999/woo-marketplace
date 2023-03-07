<?php

namespace Woo\Menu\Providers;

use Woo\Base\Events\DeletedContentEvent;
use Woo\Menu\Listeners\DeleteMenuNodeListener;
use Woo\Menu\Listeners\UpdateMenuNodeUrlListener;
use Woo\Slug\Events\UpdatedSlugEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedSlugEvent::class => [
            UpdateMenuNodeUrlListener::class,
        ],
        DeletedContentEvent::class => [
            DeleteMenuNodeListener::class,
        ],
    ];
}
