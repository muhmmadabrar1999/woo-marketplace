<?php

namespace Woo\PluginManagement\Providers;

use Woo\PluginManagement\Commands\ClearCompiledCommand;
use Woo\PluginManagement\Commands\IlluminateClearCompiledCommand as OverrideIlluminateClearCompiledCommand;
use Woo\PluginManagement\Commands\PackageDiscoverCommand;
use Woo\PluginManagement\Commands\PluginActivateAllCommand;
use Woo\PluginManagement\Commands\PluginActivateCommand;
use Woo\PluginManagement\Commands\PluginAssetsPublishCommand;
use Woo\PluginManagement\Commands\PluginDeactivateAllCommand;
use Woo\PluginManagement\Commands\PluginDeactivateCommand;
use Woo\PluginManagement\Commands\PluginDiscoverCommand;
use Woo\PluginManagement\Commands\PluginListCommand;
use Woo\PluginManagement\Commands\PluginRemoveAllCommand;
use Woo\PluginManagement\Commands\PluginRemoveCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand as IlluminateClearCompiledCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand as IlluminatePackageDiscoverCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend(IlluminatePackageDiscoverCommand::class, function () {
            return $this->app->make(PackageDiscoverCommand::class);
        });

        $this->app->extend(IlluminateClearCompiledCommand::class, function () {
            return $this->app->make(OverrideIlluminateClearCompiledCommand::class);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
                ClearCompiledCommand::class,
                PluginDiscoverCommand::class,
            ]);
        }

        $this->commands([
            PluginActivateCommand::class,
            PluginActivateAllCommand::class,
            PluginDeactivateCommand::class,
            PluginDeactivateAllCommand::class,
            PluginRemoveCommand::class,
            PluginRemoveAllCommand::class,
            PluginListCommand::class,
        ]);
    }
}
