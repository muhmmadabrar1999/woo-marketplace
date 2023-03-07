<?php

namespace Woo\Blog\Services\Abstracts;

use Woo\Blog\Models\Post;
use Woo\Blog\Repositories\Interfaces\TagInterface;
use Illuminate\Http\Request;

abstract class StoreTagServiceAbstract
{
    protected TagInterface $tagRepository;

    public function __construct(TagInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    abstract public function execute(Request $request, Post $post): void;
}
