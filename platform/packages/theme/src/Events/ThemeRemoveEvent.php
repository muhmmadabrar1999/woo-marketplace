<?php

namespace Woo\Theme\Events;

use Woo\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class ThemeRemoveEvent extends Event
{
    use SerializesModels;

    public string $theme;

    public function __construct(string $theme)
    {
        $this->theme = $theme;
    }
}
