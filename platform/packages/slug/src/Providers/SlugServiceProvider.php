<?php

namespace Woo\Slug\Providers;

use BaseHelper;
use Woo\Base\Models\BaseModel;
use Woo\Base\Traits\LoadAndPublishDataTrait;
use Woo\Page\Models\Page;
use Woo\Slug\Models\Slug;
use Woo\Slug\Repositories\Caches\SlugCacheDecorator;
use Woo\Slug\Repositories\Eloquent\SlugRepository;
use Woo\Slug\Repositories\Interfaces\SlugInterface;
use Woo\Slug\SlugHelper;
use Woo\Slug\SlugCompiler;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MacroableModels;

class SlugServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this->app->bind(SlugInterface::class, function () {
            return new SlugCacheDecorator(new SlugRepository(new Slug()));
        });

        $this->app->singleton(SlugHelper::class, function () {
            return new SlugHelper(new SlugCompiler());
        });

        $this->setNamespace('packages/slug')
            ->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['general'])
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-packages-slug-permalink',
                    'priority' => 5,
                    'parent_id' => 'cms-core-settings',
                    'name' => 'packages/slug::slug.permalink_settings',
                    'icon' => null,
                    'url' => route('slug.settings'),
                    'permissions' => ['setting.options'],
                ]);
        });

        $this->app->booted(function () {
            $this->app->register(FormServiceProvider::class);

            foreach (array_keys($this->app->make(SlugHelper::class)->supportedModels()) as $item) {
                if (! class_exists($item)) {
                    continue;
                }

                /**
                 * @var BaseModel $item
                 */
                $item::resolveRelationUsing('slugable', function ($model) {
                    return $model->morphOne(Slug::class, 'reference')->select([
                        'key',
                        'reference_type',
                        'reference_id',
                        'prefix',
                    ]);
                });

                MacroableModels::addMacro($item, 'getSlugAttribute', function () {
                    /**
                     * @var BaseModel $this
                     */
                    return $this->slugable ? $this->slugable->key : '';
                });

                MacroableModels::addMacro($item, 'getSlugIdAttribute', function () {
                    /**
                     * @var BaseModel $this
                     */
                    return $this->slugable ? $this->slugable->id : '';
                });

                MacroableModels::addMacro(
                    $item,
                    'getUrlAttribute',
                    function () {
                        /**
                         * @var BaseModel $this
                         */

                        if (! $this->slug) {
                            return url('');
                        }

                        if (get_class($this) == Page::class && BaseHelper::isHomepage($this->id)) {
                            return url('');
                        }

                        $prefix = $this->slugable ? $this->slugable->prefix : null;
                        $prefix = apply_filters(FILTER_SLUG_PREFIX, $prefix);

                        $prefix = \SlugHelper::getTranslator()->compile($prefix, $this);

                        return apply_filters(
                            'slug_filter_url',
                            url($prefix ? $prefix . '/' . $this->slug : $this->slug)
                        );
                    }
                );
            }

            $this->app->register(HookServiceProvider::class);
        });
    }

    public function provides(): array
    {
        return [
            SlugHelper::class,
        ];
    }
}
