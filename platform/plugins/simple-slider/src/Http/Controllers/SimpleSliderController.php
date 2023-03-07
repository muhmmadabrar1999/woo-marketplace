<?php

namespace Woo\SimpleSlider\Http\Controllers;

use Assets;
use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Forms\FormBuilder;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Base\Traits\HasDeleteManyItemsTrait;
use Woo\SimpleSlider\Forms\SimpleSliderForm;
use Woo\SimpleSlider\Http\Requests\SimpleSliderRequest;
use Woo\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Woo\Base\Http\Controllers\BaseController;
use Woo\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;
use Illuminate\Http\Request;
use Exception;
use Woo\SimpleSlider\Tables\SimpleSliderTable;

class SimpleSliderController extends BaseController
{
    use HasDeleteManyItemsTrait;

    protected SimpleSliderInterface $simpleSliderRepository;

    protected SimpleSliderItemInterface $simpleSliderItemRepository;

    public function __construct(
        SimpleSliderInterface $simpleSliderRepository,
        SimpleSliderItemInterface $simpleSliderItemRepository
    ) {
        $this->simpleSliderRepository = $simpleSliderRepository;
        $this->simpleSliderItemRepository = $simpleSliderItemRepository;
    }

    public function index(SimpleSliderTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.menu'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.create'));

        return $formBuilder
            ->create(SimpleSliderForm::class)
            ->removeMetaBox('slider-items')
            ->renderForm();
    }

    public function store(SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setNextUrl(route('simple-slider.edit', $simpleSlider->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        Assets::addScripts(['blockui', 'sortable'])
            ->addScriptsDirectly(['vendor/core/plugins/simple-slider/js/simple-slider-admin.js']);

        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $simpleSlider));

        page_title()->setTitle(trans('plugins/simple-slider::simple-slider.edit') . ' "' . $simpleSlider->name . '"');

        return $formBuilder
            ->create(SimpleSliderForm::class, ['model' => $simpleSlider])
            ->renderForm();
    }

    public function update(int $id, SimpleSliderRequest $request, BaseHttpResponse $response)
    {
        $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
        $simpleSlider->fill($request->input());

        $this->simpleSliderRepository->createOrUpdate($simpleSlider);

        event(new UpdatedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

        return $response
            ->setPreviousUrl(route('simple-slider.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $simpleSlider = $this->simpleSliderRepository->findOrFail($id);
            $this->simpleSliderRepository->delete($simpleSlider);

            event(new DeletedContentEvent(SIMPLE_SLIDER_MODULE_SCREEN_NAME, $request, $simpleSlider));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->simpleSliderRepository, SIMPLE_SLIDER_MODULE_SCREEN_NAME);
    }

    public function postSorting(Request $request, BaseHttpResponse $response)
    {
        foreach ($request->input('items', []) as $key => $id) {
            $this->simpleSliderItemRepository->createOrUpdate(['order' => ($key + 1)], ['id' => $id]);
        }

        return $response->setMessage(trans('plugins/simple-slider::simple-slider.update_slide_position_success'));
    }
}
