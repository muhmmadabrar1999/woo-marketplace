<?php

namespace Woo\Location\Providers;

use Illuminate\Support\ServiceProvider;
use Kris\LaravelFormBuilder\FormHelper;
use Woo\Base\Forms\FormAbstract;
use Woo\Location\Fields\SelectLocationField;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter('form_custom_fields', function (FormAbstract $form, FormHelper $formHelper) {
            if (! $formHelper->hasCustomField('selectLocation')) {
                $form->addCustomField('selectLocation', SelectLocationField::class);
            }

            return $form;
        }, 29, 2);
    }
}
