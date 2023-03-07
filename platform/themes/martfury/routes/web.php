<?php

// Custom routes
// You can delete this route group if you don't need to add your custom routes.
Route::group(['namespace' => 'Theme\WooMarketplace\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('ajax/products', 'WooMarketplaceController@ajaxGetProducts')
            ->name('public.ajax.products');

        Route::get('ajax/featured-product-categories', 'WooMarketplaceController@getFeaturedProductCategories')
            ->name('public.ajax.featured-product-categories');

        Route::get('ajax/trending-products', 'WooMarketplaceController@ajaxGetTrendingProducts')
            ->name('public.ajax.trending-products');

        Route::get('ajax/featured-brands', 'WooMarketplaceController@ajaxGetFeaturedBrands')
            ->name('public.ajax.featured-brands');

        Route::get('ajax/featured-products', 'WooMarketplaceController@ajaxGetFeaturedProducts')
            ->name('public.ajax.featured-products');

        Route::get('ajax/top-rated-products', 'WooMarketplaceController@ajaxGetTopRatedProducts')
            ->name('public.ajax.top-rated-products');

        Route::get('ajax/on-sale-products', 'WooMarketplaceController@ajaxGetOnSaleProducts')
            ->name('public.ajax.on-sale-products');

        Route::get('ajax/cart', 'WooMarketplaceController@ajaxCart')
            ->name('public.ajax.cart');

        Route::get('ajax/quick-view/{id}', 'WooMarketplaceController@getQuickView')
            ->name('public.ajax.quick-view');

        Route::get('ajax/featured-posts', 'WooMarketplaceController@ajaxGetFeaturedPosts')
            ->name('public.ajax.featured-posts');

        Route::get('ajax/related-products/{id}', 'WooMarketplaceController@ajaxGetRelatedProducts')
            ->name('public.ajax.related-products');

        Route::get('ajax/product-reviews/{id}', 'WooMarketplaceController@ajaxGetProductReviews')
            ->name('public.ajax.product-reviews');

        Route::get('ajax/search-products', 'WooMarketplaceController@ajaxSearchProducts')
            ->name('public.ajax.search-products');

        Route::post('ajax/send-download-app-links', 'WooMarketplaceController@ajaxSendDownloadAppLinks')
            ->name('public.ajax.send-download-app-links');

        Route::get('ajax/product-categories/products', 'WooMarketplaceController@ajaxGetProductsByCategoryId')
            ->name('public.ajax.product-category-products');

        Route::get('ajax/get-product-categories', 'WooMarketplaceController@ajaxGetProductCategories')
            ->name('public.ajax.get-product-categories');

        Route::get('ajax/get-flash-sale/{id}', 'WooMarketplaceController@ajaxGetFlashSale')
            ->name('public.ajax.get-flash-sale');
    });
});

Theme::routes();

Route::group(['namespace' => 'Theme\WooMarketplace\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('/', 'WooMarketplaceController@getIndex')
            ->name('public.index');

        Route::get('sitemap.xml', 'WooMarketplaceController@getSiteMap')
            ->name('public.sitemap');

        Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), 'WooMarketplaceController@getView')
            ->name('public.single');
    });
});
