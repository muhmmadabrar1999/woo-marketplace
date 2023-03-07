<?php

namespace Woo\Location\Http\Controllers;

use BaseHelper;
use Woo\Base\Events\BeforeEditContentEvent;
use Woo\Location\Http\Requests\CountryRequest;
use Woo\Location\Http\Resources\CountryResource;
use Woo\Location\Models\Country;
use Woo\Location\Repositories\Interfaces\CountryInterface;
use Woo\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Woo\Location\Tables\CountryTable;
use Woo\Base\Events\CreatedContentEvent;
use Woo\Base\Events\DeletedContentEvent;
use Woo\Base\Events\UpdatedContentEvent;
use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Location\Forms\CountryForm;
use Woo\Base\Forms\FormBuilder;

class CountryController extends BaseController
{
    protected CountryInterface $countryRepository;

    public function __construct(CountryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index(CountryTable $table)
    {
        page_title()->setTitle(trans('plugins/location::country.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/location::country.create'));

        return $formBuilder->create(CountryForm::class)->renderForm();
    }

    public function store(CountryRequest $request, BaseHttpResponse $response)
    {
        $country = $this->countryRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(COUNTRY_MODULE_SCREEN_NAME, $request, $country));

        return $response
            ->setPreviousUrl(route('country.index'))
            ->setNextUrl(route('country.edit', $country->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder, Request $request)
    {
        $country = $this->countryRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $country));

        page_title()->setTitle(trans('plugins/location::country.edit') . ' "' . $country->name . '"');

        return $formBuilder->create(CountryForm::class, ['model' => $country])->renderForm();
    }

    public function update(int $id, CountryRequest $request, BaseHttpResponse $response)
    {
        $country = $this->countryRepository->findOrFail($id);

        $country->fill($request->input());

        $this->countryRepository->createOrUpdate($country);

        event(new UpdatedContentEvent(COUNTRY_MODULE_SCREEN_NAME, $request, $country));

        return $response
            ->setPreviousUrl(route('country.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Request $request, int $id, BaseHttpResponse $response)
    {
        try {
            $country = $this->countryRepository->findOrFail($id);

            $this->countryRepository->delete($country);

            event(new DeletedContentEvent(COUNTRY_MODULE_SCREEN_NAME, $request, $country));

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
            $country = $this->countryRepository->findOrFail($id);
            $this->countryRepository->delete($country);
            event(new DeletedContentEvent(COUNTRY_MODULE_SCREEN_NAME, $request, $country));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getList(Request $request, BaseHttpResponse $response)
    {
        $keyword = BaseHelper::stringify($request->input('q'));

        if (! $keyword) {
            return $response->setData([]);
        }

        $data = $this->countryRepository->advancedGet([
            'condition' => [
                ['countries.name', 'LIKE', '%' . $keyword . '%'],
            ],
            'select' => ['countries.id', 'countries.name'],
            'take' => 10,
            'order_by' => ['order' => 'ASC', 'name' => 'ASC'],
        ]);

        $data->prepend(new Country(['id' => 0, 'name' => trans('plugins/location::city.select_country')]));

        return $response->setData(CountryResource::collection($data));
    }
}
