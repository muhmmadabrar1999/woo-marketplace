<?php

namespace Woo\Ecommerce\Forms;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Base\Forms\FormAbstract;
use Woo\Ecommerce\Http\Requests\ProductTagRequest;
use Woo\Ecommerce\Models\ProductTag;

class ProductTagForm extends FormAbstract
{
    public function buildForm()
    {
        $this
            ->setupModel(new ProductTag())
            ->setValidatorClass(ProductTagRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'form-control address',
                ],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
