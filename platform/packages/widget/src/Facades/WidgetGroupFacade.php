<?php

namespace Woo\Widget\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Widget\WidgetGroupCollection
 */
class WidgetGroupFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Woo.widget-group-collection';
    }
}
