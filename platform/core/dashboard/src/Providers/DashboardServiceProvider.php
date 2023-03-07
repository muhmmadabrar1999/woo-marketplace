<?php

namespace Woo\Dashboard\Providers;

use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\Dashboard\Models\DashboardWidget;
use Woo\Dashboard\Models\DashboardWidgetSetting;
use Woo\Dashboard\Repositories\Caches\DashboardWidgetCacheDecorator;
use Woo\Dashboard\Repositories\Caches\DashboardWidgetSettingCacheDecorator;
use Woo\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Woo\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Woo\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Woo\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetCacheDecorator(
                new DashboardWidgetRepository(new DashboardWidget())
            );
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingCacheDecorator(
                new DashboardWidgetSettingRepository(new DashboardWidgetSetting())
            );
        });
    }

    public function boot(): void
    {
        $this->setNamespace('core/dashboard')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-dashboard',
                    'priority' => 0,
                    'parent_id' => null,
                    'name' => 'core/base::layouts.dashboard',
                    'icon' => 'fa fa-home',
                    'url' => route('dashboard.index'),
                    'permissions' => [],
                ]);
        });
    }
}
