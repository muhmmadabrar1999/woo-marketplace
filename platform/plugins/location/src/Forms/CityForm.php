<?php

namespace Woo\Location\Forms;

use Assets;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Base\Forms\FormAbstract;
use Woo\Location\Http\Requests\CityRequest;
use Woo\Location\Models\City;
use Woo\Location\Repositories\Interfaces\CountryInterface;
use Woo\Location\Repositories\Interfaces\StateInterface;

class CityForm extends FormAbstract
{
    protected CountryInterface $countryRepository;

    protected StateInterface $stateRepository;

    public function __construct(CountryInterface $countryRepository, StateInterface $stateRepository)
    {
        parent::__construct();

        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
    }

    public function buildForm(): void
    {
        Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');

        $countries = $this->countryRepository->pluck('countries.name', 'countries.id');

        $states = [];
        if ($this->getModel()) {
            $states = $this->stateRepository->pluck(
                'states.name',
                'states.id',
                [['country_id', '=', $this->model->country_id]]
            );
        }

        $this
            ->setupModel(new City())
            ->setValidatorClass(CityRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('country_id', 'customSelect', [
                'label' => trans('plugins/location::city.country'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'id' => 'country_id',
                    'class' => 'select-search-full',
                    'data-type' => 'country',
                ],
                'choices' => [0 => trans('plugins/location::city.select_country')] + $countries,
            ])
            ->add('state_id', 'customSelect', [
                'label' => trans('plugins/location::city.state'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'id' => 'state_id',
                    'data-url' => route('ajax.states-by-country'),
                    'class' => 'select-search-full',
                    'data-type' => 'state',
                ],
                'choices' => ($this->getModel()->state_id ?
                        [
                            $this->model->state->id => $this->model->state->name,
                        ]
                        :
                        [0 => trans('plugins/location::city.select_state')]) + $states,
            ])
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('is_default', 'onOff', [
                'label' => trans('core/base::forms.is_default'),
                'label_attr' => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
