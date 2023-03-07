<?php

namespace Woo\Faq\Forms;

use Woo\Base\Forms\FormAbstract;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Faq\Http\Requests\FaqCategoryRequest;
use Woo\Faq\Models\FaqCategory;

class FaqCategoryForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new FaqCategory())
            ->setValidatorClass(FaqCategoryRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('order', 'number', [
                'label' => trans('core/base::forms.order'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
