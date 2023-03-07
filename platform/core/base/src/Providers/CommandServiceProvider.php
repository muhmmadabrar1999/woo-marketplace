<?php

namespace Woo\Base\Providers;

use Woo\Base\Commands\CleanupSystemCommand;
use Woo\Base\Commands\ClearLogCommand;
use Woo\Base\Commands\ExportDatabaseCommand;
use Woo\Base\Commands\FetchGoogleFontsCommand;
use Woo\Base\Commands\InstallCommand;
use Woo\Base\Commands\PublishAssetsCommand;
use Woo\Base\Commands\UpdateCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            ClearLogCommand::class,
            InstallCommand::class,
            UpdateCommand::class,
            PublishAssetsCommand::class,
            CleanupSystemCommand::class,
            ExportDatabaseCommand::class,
            FetchGoogleFontsCommand::class,
        ]);
    }
}
