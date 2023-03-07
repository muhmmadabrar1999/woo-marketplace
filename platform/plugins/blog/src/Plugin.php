<?php

namespace Woo\Blog;

use Woo\Blog\Models\Category;
use Woo\Blog\Models\Tag;
use Woo\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Woo\Menu\Repositories\Interfaces\MenuNodeInterface;
use Woo\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Woo\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts_translations');
        Schema::dropIfExists('categories_translations');
        Schema::dropIfExists('tags_translations');

        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_posts_recent']);

        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Category::class]);
        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Tag::class]);

        Setting::query()
            ->whereIn('key', [
                'blog_post_schema_enabled',
                'blog_post_schema_type',
            ])
            ->delete();
    }
}
