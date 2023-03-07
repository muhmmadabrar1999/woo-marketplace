<?php

namespace Woo\Marketplace\Http\Controllers\Fronts;

use Assets;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Marketplace\Http\Requests\SettingRequest;
use Woo\Marketplace\Models\Store;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;
use Illuminate\Contracts\Config\Repository;
use MarketplaceHelper;
use RvMedia;
use SlugHelper;

class SettingController
{
    public function __construct(Repository $config)
    {
        Assets::setConfig($config->get('plugins.marketplace.assets', []));
    }

    public function index()
    {
        page_title()->setTitle(__('Settings'));

        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');

        $store = auth('customer')->user()->store;

        return MarketplaceHelper::view('dashboard.settings', compact('store'));
    }

    public function saveSettings(SettingRequest $request, StoreInterface $storeRepository, BaseHttpResponse $response)
    {
        $store = auth('customer')->user()->store;

        $existing = SlugHelper::getSlug($request->input('slug'), SlugHelper::getPrefix(Store::class));

        if ($existing && $existing->reference_id != $store->id) {
            return $response->setError()->setMessage(__('Shop URL is existing. Please choose another one!'));
        }

        if ($request->hasFile('logo_input')) {
            $result = RvMedia::handleUpload($request->file('logo_input'), 0, 'stores');
            if (! $result['error']) {
                $file = $result['data'];
                $request->merge(['logo' => $file->url]);
            }
        }

        $store->fill($request->input());

        $storeRepository->createOrUpdate($store);

        $customer = $store->customer;

        if ($customer && $customer->id) {
            $vendorInfo = $customer->vendorInfo;
            $vendorInfo->payout_payment_method = $request->input('payout_payment_method');
            $vendorInfo->bank_info = $request->input('bank_info', []);
            $vendorInfo->tax_info = $request->input('tax_info', []);
            $vendorInfo->save();
        }

        $request->merge(['is_slug_editable' => 1]);

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setNextUrl(route('marketplace.vendor.settings'))
            ->setMessage(__('Update successfully!'));
    }
}
