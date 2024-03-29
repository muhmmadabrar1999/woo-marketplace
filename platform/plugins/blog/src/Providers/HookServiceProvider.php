<?php

namespace Woo\Blog\Providers;

use Assets;
use BaseHelper;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Blog\Models\Category;
use Woo\Blog\Models\Post;
use Woo\Blog\Models\Tag;
use Woo\Blog\Services\BlogService;
use Woo\Dashboard\Supports\DashboardWidgetInstance;
use Woo\Page\Models\Page;
use Woo\Page\Repositories\Interfaces\PageInterface;
use Woo\Shortcode\Compilers\Shortcode;
use Woo\Slug\Models\Slug;
use Eloquent;
use Html;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Menu;
use RvMedia;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (defined('MENU_ACTION_SIDEBAR_OPTIONS')) {
            Menu::addMenuOptionModel(Category::class);
            Menu::addMenuOptionModel(Tag::class);
            add_action(MENU_ACTION_SIDEBAR_OPTIONS, [$this, 'registerMenuOptions'], 2);
        }
        add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 21, 2);
        add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, [$this, 'handleSingleView'], 2);
        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            add_filter(PAGE_FILTER_FRONT_PAGE_CONTENT, [$this, 'renderBlogPage'], 2, 2);
            add_filter(PAGE_FILTER_PAGE_NAME_IN_ADMIN_LIST, [$this, 'addAdditionNameToPageName'], 147, 2);
        }

        Event::listen(RouteMatched::class, function () {
            if (function_exists('admin_bar')) {
                admin_bar()->registerLink(trans('plugins/blog::posts.post'), route('posts.create'), 'add-new', 'posts.create');
            }
        });

        if (function_exists('add_shortcode')) {
            add_shortcode(
                'blog-posts',
                trans('plugins/blog::base.short_code_name'),
                trans('plugins/blog::base.short_code_description'),
                [$this, 'renderBlogPosts']
            );
            shortcode()->setAdminConfig('blog-posts', function ($attributes, $content) {
                return view('plugins/blog::partials.posts-short-code-admin-config', compact('attributes', 'content'))
                    ->render();
            });
        }

        if (function_exists('theme_option')) {
            add_action(RENDERING_THEME_OPTIONS_PAGE, [$this, 'addThemeOptions'], 35);
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            add_action(BASE_ACTION_META_BOXES, [$this, 'addLanguageChooser'], 55, 2);
        }

        if (defined('THEME_FRONT_HEADER') && setting('blog_post_schema_enabled', 1)) {
            add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $post) {
                add_filter(THEME_FRONT_HEADER, function ($html) use ($post) {
                    if (get_class($post) != Post::class) {
                        return $html;
                    }

                    $schemaType = setting('blog_post_schema_type', 'NewsArticle');

                    if (! in_array($schemaType, ['NewsArticle', 'News', 'Article', 'BlogPosting'])) {
                        $schemaType = 'NewsArticle';
                    }

                    $schema = [
                        '@context' => 'https://schema.org',
                        '@type' => $schemaType,
                        'mainEntityOfPage' => [
                            '@type' => 'WebPage',
                            '@id' => $post->url,
                        ],
                        'headline' => BaseHelper::clean($post->name),
                        'description' => BaseHelper::clean($post->description),
                        'image' => [
                            '@type' => 'ImageObject',
                            'url' => RvMedia::getImageUrl($post->image, null, false, RvMedia::getDefaultImage()),
                        ],
                        'author' => [
                            '@type' => 'Person',
                            'url' => route('public.index'),
                            'name' => class_exists($post->author_type) ? $post->author->name : '',
                        ],
                        'publisher' => [
                            '@type' => 'Organization',
                            'name' => theme_option('site_title'),
                            'logo' => [
                                '@type' => 'ImageObject',
                                'url' => RvMedia::getImageUrl(theme_option('logo')),
                            ],
                        ],
                        'datePublished' => $post->created_at->toDateString(),
                        'dateModified' => $post->updated_at->toDateString(),
                    ];

                    return $html . Html::tag('script', json_encode($schema), ['type' => 'application/ld+json'])
                            ->toHtml();
                }, 35);
            }, 35, 2);
        }

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 193);
    }

    public function addThemeOptions()
    {
        $pages = $this->app->make(PageInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

        theme_option()
            ->setSection([
                'title' => 'Blog',
                'desc' => 'Theme options for Blog',
                'id' => 'opt-text-subsection-blog',
                'subsection' => true,
                'icon' => 'fa fa-edit',
                'fields' => [
                    [
                        'id' => 'blog_page_id',
                        'type' => 'customSelect',
                        'label' => trans('plugins/blog::base.blog_page_id'),
                        'attributes' => [
                            'name' => 'blog_page_id',
                            'list' => ['' => trans('plugins/blog::base.select')] + $pages,
                            'value' => '',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'number_of_posts_in_a_category',
                        'type' => 'number',
                        'label' => trans('plugins/blog::base.number_posts_per_page_in_category'),
                        'attributes' => [
                            'name' => 'number_of_posts_in_a_category',
                            'value' => 12,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                    [
                        'id' => 'number_of_posts_in_a_tag',
                        'type' => 'number',
                        'label' => trans('plugins/blog::base.number_posts_per_page_in_tag'),
                        'attributes' => [
                            'name' => 'number_of_posts_in_a_tag',
                            'value' => 12,
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Register sidebar options in menu
     */
    public function registerMenuOptions(): void
    {
        if (Auth::user()->hasPermission('categories.index')) {
            Menu::registerMenuOptions(Category::class, trans('plugins/blog::categories.menu'));
        }

        if (Auth::user()->hasPermission('tags.index')) {
            Menu::registerMenuOptions(Tag::class, trans('plugins/blog::tags.menu'));
        }
    }

    public function registerDashboardWidgets(array $widgets, Collection $widgetSettings): array
    {
        if (! Auth::user()->hasPermission('posts.index')) {
            return $widgets;
        }

        Assets::addScriptsDirectly(['/vendor/core/plugins/blog/js/blog.js']);

        return (new DashboardWidgetInstance())
            ->setPermission('posts.index')
            ->setKey('widget_posts_recent')
            ->setTitle(trans('plugins/blog::posts.widget_posts_recent'))
            ->setIcon('fas fa-edit')
            ->setColor('#f3c200')
            ->setRoute(route('posts.widget.recent-posts'))
            ->setBodyClass('scroll-table')
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }

    public function handleSingleView(Slug|array $slug): Eloquent|array
    {
        return (new BlogService())->handleFrontRoutes($slug);
    }

    public function renderBlogPosts(Shortcode $shortcode): array|string
    {
        $posts = get_all_posts(true, (int)$shortcode->paginate);

        $view = 'plugins/blog::themes.templates.posts';
        $themeView = Theme::getThemeNamespace() . '::views.templates.posts';

        if (view()->exists($themeView)) {
            $view = $themeView;
        }

        return view($view, compact('posts'))->render();
    }

    public function renderBlogPage(?string $content, Page $page): ?string
    {
        if ($page->id == theme_option('blog_page_id', setting('blog_page_id'))) {
            $view = 'plugins/blog::themes.loop';

            if (view()->exists(Theme::getThemeNamespace() . '::views.loop')) {
                $view = Theme::getThemeNamespace() . '::views.loop';
            }

            return view($view, [
                'posts' => get_all_posts(true, (int)theme_option('number_of_posts_in_a_category', 12)),
            ])->render();
        }

        return $content;
    }

    public function addAdditionNameToPageName(?string $name, Page $page): ?string
    {
        if ($page->id == theme_option('blog_page_id', setting('blog_page_id'))) {
            $subTitle = Html::tag('span', trans('plugins/blog::base.blog_page'), ['class' => 'additional-page-name'])
                ->toHtml();

            if (Str::contains($name, ' —')) {
                return $name . ', ' . $subTitle;
            }

            return $name . ' —' . $subTitle;
        }

        return $name;
    }

    public function addLanguageChooser(string $priority, Model $model): void
    {
        if ($priority == 'head' && $model instanceof Category) {
            $route = 'categories.index';

            echo view('plugins/language::partials.admin-list-language-chooser', compact('route'))->render();
        }
    }

    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/blog::settings')->render();
    }
}
