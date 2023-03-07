<?php

namespace Woo\LanguageAdvanced\Providers;

use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\LanguageAdvanced\Listeners\AddDefaultTranslations;
use Woo\LanguageAdvanced\Listeners\PriorityLanguageAdvancedPluginListener;
use Woo\LanguageAdvanced\Listeners\ClearCacheAfterUpdateData;
use Woo\PluginManagement\Events\ActivatedPluginEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreatedContentEvent::class => [
            AddDefaultTranslations::class,
        ],
        UpdatedContentEvent::class => [
            ClearCacheAfterUpdateData::class,
        ],
        ActivatedPluginEvent::class => [
            PriorityLanguageAdvancedPluginListener::class,
        ],
    ];
}
