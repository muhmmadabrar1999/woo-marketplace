<?php

namespace Woo\Shippo\Providers;

use Woo\Shippo\Commands\InitShippoCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            InitShippoCommand::class,
        ]);
    }
}
