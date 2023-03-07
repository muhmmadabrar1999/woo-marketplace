<?php

namespace Woo\Marketplace\Http\Controllers\Fronts;

use Assets;
use Woo\Ecommerce\Repositories\Interfaces\ReviewInterface;
use Woo\Marketplace\Tables\ReviewTable;
use MarketplaceHelper;

class ReviewController
{
    protected ReviewInterface $reviewRepository;

    public function __construct(ReviewInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function index(ReviewTable $table)
    {
        page_title()->setTitle(__('Reviews'));

        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/review.css');

        return $table->render(MarketplaceHelper::viewPath('dashboard.table.base'));
    }
}
