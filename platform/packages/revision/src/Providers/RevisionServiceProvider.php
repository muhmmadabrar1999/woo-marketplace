<?php

namespace Woo\Revision\Providers;

use Woo\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\ServiceProvider;

class RevisionServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('packages/revision')
            ->loadAndPublishViews()
            ->loadAndPublishConfigurations(['general'])
            ->loadMigrations()
            ->publishAssets();

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
