<?php

namespace Woo\PluginManagement\Events;

use Woo\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class ActivatedPluginEvent extends Event
{
    use SerializesModels;

    public string $plugin;

    public function __construct(string $plugin)
    {
        $this->plugin = $plugin;
    }
}
