<?php

namespace Woo\Theme\Events;

use Woo\Base\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Routing\Router;

class ThemeRoutingAfterEvent extends Event
{
    use SerializesModels;

    public Router $router;

    public function __construct()
    {
        $this->router = app('router');
    }
}
