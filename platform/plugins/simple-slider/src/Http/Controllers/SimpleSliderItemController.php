<?php

namespace Woo\SimpleSlider\Http\Controllers;

use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Forms\FormBuilder;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\SimpleSlider\Forms\SimpleSliderItemForm;
use Woo\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use Woo\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Woo\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Woo\SimpleSlider\Tables\SimpleSliderItemTable;

class SimpleSliderItemController extends BaseController
{
    protected SimpleSliderItemInterface $simpleSliderItemRepository;

    public function __construct(SimpleSliderItemInterface $simpleSliderItemRepository)
    {
        $this->simpleSliderItemRepository = $simpleSliderItemRepository;
    }

    public function index(SimpleSliderItemTable $dataTable)
    {
        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        return $formBuilder->create(SimpleSliderItemForm::class)
            ->setTitle(trans('plugins/simple-slider::simple-slider.create_new_slide'))
            ->setUseInlineJs(true)
            ->renderForm();
    }

    public function store(SimpleSliderItemRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderItemRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $simpleSliderItem = $this->simpleSliderItemRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $simpleSliderItem));

        return $formBuilder->create(SimpleSliderItemForm::class, ['model' => $simpleSliderItem])
            ->setTitle(trans('plugins/simple-slider::simple-slider.edit_slide', ['id' => $simpleSliderItem->id]))
            ->setUseInlineJs(true)
            ->renderForm();
    }

    public function update(int $id, SimpleSliderItemRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderItemRepository->findOrFail($id);
        $simpleSlider->fill($request->input());

        $this->simpleSliderItemRepository->createOrUpdate($simpleSlider);

        event(new UpdatedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int $id)
    {
        $slider = $this->simpleSliderItemRepository->findOrFail($id);

        return view('plugins/simple-slider::partials.delete', compact('slider'))->render();
    }

    public function postDelete(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $simpleSlider = $this->simpleSliderItemRepository->findOrFail($id);
            $this->simpleSliderItemRepository->delete($simpleSlider);

            event(new DeletedContentEvent(SIMPLE_SLIDER_ITEM_MODULE_SCREEN_NAME, $request, $simpleSlider));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}