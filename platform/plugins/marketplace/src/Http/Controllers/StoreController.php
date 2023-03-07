<?php

namespace Woo\Marketplace\Http\Controllers;

use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Forms\FormBuilder;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Marketplace\Forms\StoreForm;
use Woo\Marketplace\Http\Requests\StoreRequest;
use Woo\Marketplace\Repositories\Interfaces\RevenueInterface;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;
use Woo\Marketplace\Tables\StoreTable;
use Exception;
use Illuminate\Http\Request;

class StoreController extends BaseController
{
    protected StoreInterface $storeRepository;

    protected RevenueInterface $revenueRepository;

    public function __construct(StoreInterface $storeRepository, RevenueInterface $revenueRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->revenueRepository = $revenueRepository;
    }

    public function index(StoreTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::store.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/marketplace::store.create'));

        return $formBuilder->create(StoreForm::class)->renderForm();
    }

    public function store(StoreRequest $request, BaseHttpResponse $response)
    {
        $store = $this->storeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setNextUrl(route('marketplace.store.edit', $store->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $store = $this->storeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $store));

        page_title()->setTitle(trans('plugins/marketplace::store.edit') . ' "' . $store->name . '"');

        return $formBuilder->create(StoreForm::class, ['model' => $store])->renderForm();
    }

    public function update(int $id, StoreRequest $request, BaseHttpResponse $response)
    {
        $store = $this->storeRepository->findOrFail($id);

        $store->fill($request->input());

        $this->storeRepository->createOrUpdate($store);

        $customer = $store->customer;
        if ($customer && $customer->id) {
            $vendorInfo = $customer->vendorInfo;
            $vendorInfo->payout_payment_method = $request->input('payout_payment_method');
            $vendorInfo->bank_info = $request->input('bank_info', []);
            $vendorInfo->tax_info = $request->input('tax_info', []);
            $vendorInfo->save();
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

        return $response
            ->setPreviousUrl(route('marketplace.store.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $store = $this->storeRepository->findOrFail($id);

            $this->storeRepository->delete($store);

            event(new DeletedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $store = $this->storeRepository->findOrFail($id);
            $this->storeRepository->delete($store);
            event(new DeletedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $store));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
