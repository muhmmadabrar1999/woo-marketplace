<?php

namespace Woo\Optimize\Facades;

use Woo\Optimize\Supports\Optimizer;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Woo\Optimize\Supports\Optimizer
 */
class OptimizerFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Optimizer::class;
    }
}
