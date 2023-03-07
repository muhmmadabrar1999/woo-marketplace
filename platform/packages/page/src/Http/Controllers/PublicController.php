<?php

namespace Woo\Page\Http\Controllers;

use Woo\Page\Models\Page;
use Woo\Page\Services\PageService;
use Woo\Theme\Events\RenderingSingleEvent;
use Illuminate\Routing\Controller;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    public function getPage(string $slug, PageService $pageService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Page::class));

        if (! $slug) {
            abort(404);
        }

        $data = $pageService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(url(SlugHelper::getPrefix(Page::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
    }
}
