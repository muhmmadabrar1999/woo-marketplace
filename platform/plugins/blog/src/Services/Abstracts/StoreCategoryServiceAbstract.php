<?php

namespace Woo\Blog\Services\Abstracts;

use Woo\Blog\Models\Post;
use Woo\Blog\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;

abstract class StoreCategoryServiceAbstract
{
    protected CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    abstract public function execute(Request $request, Post $post): void;
}
