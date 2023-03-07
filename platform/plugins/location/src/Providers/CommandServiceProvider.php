<?php

namespace Woo\Location\Providers;

use Woo\Location\Commands\MigrateLocationCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            MigrateLocationCommand::class,
        ]);
    }
}
