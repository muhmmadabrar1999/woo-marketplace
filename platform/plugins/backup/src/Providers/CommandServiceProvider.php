<?php

namespace Woo\Backup\Providers;

use Woo\Backup\Commands\BackupCleanCommand;
use Woo\Backup\Commands\BackupCreateCommand;
use Woo\Backup\Commands\BackupListCommand;
use Woo\Backup\Commands\BackupRemoveCommand;
use Woo\Backup\Commands\BackupRestoreCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                BackupCreateCommand::class,
                BackupRestoreCommand::class,
                BackupRemoveCommand::class,
                BackupListCommand::class,
                BackupCleanCommand::class,
            ]);
        }
    }
}
