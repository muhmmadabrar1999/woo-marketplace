<?php

namespace Woo\Marketplace\Http\Controllers;

use Assets;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Marketplace\Enums\RevenueTypeEnum;
use Woo\Marketplace\Http\Requests\StoreRevenueRequest;
use Woo\Marketplace\Repositories\Interfaces\RevenueInterface;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;
use Woo\Marketplace\Tables\StoreRevenueTable;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoreRevenueController extends BaseController
{
    protected StoreInterface $storeRepository;

    protected RevenueInterface $revenueRepository;

    public function __construct(StoreInterface $storeRepository, RevenueInterface $revenueRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->revenueRepository = $revenueRepository;
    }

    public function index(StoreRevenueTable $table)
    {
        return $table->renderTable();
    }

    public function view(int $id, StoreRevenueTable $table)
    {
        $store = $this->storeRepository->findOrFail($id);
        $customer = $store->customer;

        if (! $customer->id) {
            abort(404);
        }

        Assets::addScriptsDirectly(['vendor/core/plugins/marketplace/js/store-revenue.js']);
        $table->setAjaxUrl(route('marketplace.store.revenue.index', $customer->id));
        page_title()->setTitle(trans('plugins/marketplace::revenue.view_store', ['store' => $store->name]));

        return view('plugins/marketplace::stores.index', compact('table', 'store', 'customer'))->render();
    }

    public function store(int $id, StoreRevenueRequest $request, BaseHttpResponse $response)
    {
        $store = $this->storeRepository->findOrFail($id);

        $customer = $store->customer;

        if (! $customer->id) {
            abort(404);
        }

        $vendorInfo = $customer->vendorInfo;

        $revenue = $this->revenueRepository->getModel();
        $amount = $request->input('amount');
        $data = [
            'fee' => 0,
            'currency' => get_application_currency()->title,
            'current_balance' => $customer->balance,
            'customer_id' => $customer->getKey(),
            'order_id' => 0,
            'user_id' => Auth::id(),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'amount' => $amount,
            'sub_amount' => $amount,
        ];

        if ($request->input('type') == RevenueTypeEnum::ADD_AMOUNT) {
            $vendorInfo->total_revenue += $amount;
            $vendorInfo->balance += $amount;
        } else {
            $vendorInfo->total_revenue -= $amount;
            $vendorInfo->balance -= $amount;
        }

        try {
            DB::beginTransaction();

            $revenue->fill($data);
            $revenue->save();
            $vendorInfo->save();

            DB::commit();
        } catch (Throwable | Exception $th) {
            DB::rollBack();

            return $response
                ->setError()
                ->setMessage($th->getMessage());
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setData(['balance' => format_price($customer->balance)])
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
