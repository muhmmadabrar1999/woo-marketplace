<?php

namespace Woo\Widget\Facades;

use Woo\Widget\WidgetGroup;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Widget\Factories\WidgetFactory
 */
class WidgetFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Woo.widget';
    }

    public static function group(string $name): WidgetGroup
    {
        return app('Woo.widget-group-collection')->group($name);
    }
}
