<?php

namespace Woo\Language\Providers;

use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Language\Listeners\ActivatedPluginListener;
use Woo\Language\Listeners\AddHrefLangListener;
use Woo\Language\Listeners\CreatedContentListener;
use Woo\Language\Listeners\DeletedContentListener;
use Woo\Language\Listeners\ThemeRemoveListener;
use Woo\Language\Listeners\UpdatedContentListener;
use Woo\PluginManagement\Events\ActivatedPluginEvent;
use Woo\Theme\Events\RenderingSingleEvent;
use Woo\Theme\Events\ThemeRemoveEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        ThemeRemoveEvent::class => [
            ThemeRemoveListener::class,
        ],
        ActivatedPluginEvent::class => [
            ActivatedPluginListener::class,
        ],
        RenderingSingleEvent::class => [
            AddHrefLangListener::class,
        ],
    ];
}
