<?php

namespace Woo\Blog\Services;

use Woo\Blog\Models\Post;
use Woo\Blog\Services\Abstracts\StoreCategoryServiceAbstract;
use Illuminate\Http\Request;

class StoreCategoryService extends StoreCategoryServiceAbstract
{
    public function execute(Request $request, Post $post): void
    {
        $categories = $request->input('categories');
        if (! empty($categories) && is_array($categories)) {
            $post->categories()->sync($categories);
        }
    }
}
