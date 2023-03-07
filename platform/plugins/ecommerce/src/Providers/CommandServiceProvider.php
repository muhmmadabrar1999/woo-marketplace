<?php

namespace Woo\Ecommerce\Providers;

use Woo\Ecommerce\Commands\BulkImportProductCommand;
use Woo\Ecommerce\Commands\SendAbandonedCartsEmailCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            SendAbandonedCartsEmailCommand::class,
            BulkImportProductCommand::class,
        ]);
    }
}
