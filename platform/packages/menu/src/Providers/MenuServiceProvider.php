<?php

namespace Woo\Menu\Providers;

use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\Menu\Models\Menu as MenuModel;
use Woo\Menu\Models\MenuLocation;
use Woo\Menu\Models\MenuNode;
use Woo\Menu\Repositories\Caches\MenuCacheDecorator;
use Woo\Menu\Repositories\Caches\MenuLocationCacheDecorator;
use Woo\Menu\Repositories\Caches\MenuNodeCacheDecorator;
use Woo\Menu\Repositories\Eloquent\MenuLocationRepository;
use Woo\Menu\Repositories\Eloquent\MenuNodeRepository;
use Woo\Menu\Repositories\Eloquent\MenuRepository;
use Woo\Menu\Repositories\Interfaces\MenuInterface;
use Woo\Menu\Repositories\Interfaces\MenuLocationInterface;
use Woo\Menu\Repositories\Interfaces\MenuNodeInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('packages/menu')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuCacheDecorator(
                new MenuRepository(new MenuModel())
            );
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeCacheDecorator(
                new MenuNodeRepository(new MenuNode())
            );
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationCacheDecorator(
                new MenuLocationRepository(new MenuLocation())
            );
        });

        $this
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-core-menu',
                    'priority' => 2,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'packages/menu::menu.name',
                    'icon' => null,
                    'url' => route('menus.index'),
                    'permissions' => ['menus.index'],
                ]);

            if (! defined('THEME_MODULE_SCREEN_NAME')) {
                dashboard_menu()
                    ->registerItem([
                        'id' => 'cms-core-appearance',
                        'priority' => 996,
                        'parent_id' => null,
                        'name' => 'packages/theme::theme.appearance',
                        'icon' => 'fa fa-paint-brush',
                        'url' => '#',
                        'permissions' => [],
                    ]);
            }

            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('packages/menu::menu.name'), route('menus.index'), 'appearance', 'menus.index');
            }
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
