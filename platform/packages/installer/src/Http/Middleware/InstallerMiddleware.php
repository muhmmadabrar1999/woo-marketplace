<?php

namespace Woo\Installer\Http\Middleware;

use Woo\Base\Supports\Helper;
use Illuminate\Support\Facades\File;

abstract class InstallerMiddleware
{
    public function alreadyInstalled(): bool
    {
        if (! config('packages.installer.installer.enabled')) {
            return true;
        }

        if (Helper::isConnectedDatabase() && ! File::exists(storage_path('installing'))) {
            return true;
        }

        return File::exists(storage_path('installed'));
    }
}
