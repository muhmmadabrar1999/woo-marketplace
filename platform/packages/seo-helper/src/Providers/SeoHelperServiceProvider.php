<?php

namespace Woo\SeoHelper\Providers;

use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\SeoHelper\Contracts\SeoHelperContract;
use Woo\SeoHelper\Contracts\SeoMetaContract;
use Woo\SeoHelper\Contracts\SeoOpenGraphContract;
use Woo\SeoHelper\Contracts\SeoTwitterContract;
use Woo\SeoHelper\SeoHelper;
use Woo\SeoHelper\SeoMeta;
use Woo\SeoHelper\SeoOpenGraph;
use Woo\SeoHelper\SeoTwitter;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/12/2015 14:09 PM
 */
class SeoHelperServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(SeoMetaContract::class, SeoMeta::class);
        $this->app->bind(SeoHelperContract::class, SeoHelper::class);
        $this->app->bind(SeoOpenGraphContract::class, SeoOpenGraph::class);
        $this->app->bind(SeoTwitterContract::class, SeoTwitter::class);

        $this->setNamespace('packages/seo-helper')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
