<?php

namespace Woo\Language\Listeners;

use Woo\Language\Plugin;
use Exception;

class ActivatedPluginListener
{
    public function handle(): void
    {
        try {
            Plugin::activated();
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
