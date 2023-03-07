<?php

namespace Woo\Marketplace\Http\Controllers;

use Assets;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Base\Supports\Helper;
use Woo\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Woo\Marketplace\Http\Requests\MarketPlaceSettingFormRequest;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;
use Woo\Setting\Supports\SettingStore;
use Illuminate\Support\Str;
use MarketplaceHelper;

class MarketplaceController extends BaseController
{
    protected SettingStore $settingStore;

    protected StoreInterface $storeRepository;

    public function __construct(SettingStore $settingStore, StoreInterface $storeRepository)
    {
        $this->settingStore = $settingStore;
        $this->storeRepository = $storeRepository;
    }

    public function getSettings(ProductCategoryInterface $productCategoryRepository)
    {
        Assets::addScriptsDirectly('vendor/core/core/setting/js/setting.js')
            ->addStylesDirectly('vendor/core/core/setting/css/setting.css');

        Assets::addStylesDirectly('vendor/core/core/base/libraries/tagify/tagify.css')
            ->addScriptsDirectly([
                'vendor/core/core/base/libraries/tagify/tagify.js',
                'vendor/core/core/base/js/tags.js',
                'vendor/core/plugins/marketplace/js/marketplace-setting.js',
            ]);

        page_title()->setTitle(trans('plugins/marketplace::marketplace.settings.name'));

        $productCategories = $productCategoryRepository->all();
        $commissionEachCategory = [];

        if (MarketplaceHelper::isCommissionCategoryFeeBasedEnabled()) {
            $commissionEachCategory = $this->storeRepository->getCommissionEachCategory();
        }

        return view('plugins/marketplace::settings.index', compact('productCategories', 'commissionEachCategory'));
    }

    public function postSettings(MarketPlaceSettingFormRequest $request, BaseHttpResponse $response)
    {
        $settingKey = MarketplaceHelper::getSettingKey();
        $filtered = collect($request->all())->filter(function ($value, $key) use ($settingKey) {
            return Str::startsWith($key, $settingKey);
        });

        if ($request->input('marketplace_enable_commission_fee_for_each_category')) {
            $commissionByCategories = $request->input('commission_by_category');
            $this->storeRepository->handleCommissionEachCategory($commissionByCategories);
        }

        $preVerifyVendor = MarketplaceHelper::getSetting('verify_vendor', 1);

        foreach ($filtered as $key => $settingValue) {
            if ($key == $settingKey . 'fee_per_order') {
                $settingValue = $settingValue < 0 ? 0 : min($settingValue, 100);
            }

            $this->settingStore->set($key, $settingValue);
        }

        $this->settingStore->save();

        if ($preVerifyVendor != MarketplaceHelper::getSetting('verify_vendor', 1)) {
            Helper::clearCache();
        }

        return $response
            ->setNextUrl(route('marketplace.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
