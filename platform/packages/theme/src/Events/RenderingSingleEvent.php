<?php

namespace Woo\Theme\Events;

use Woo\Base\Events\Event;
use Woo\Slug\Models\Slug;
use Illuminate\Queue\SerializesModels;

class RenderingSingleEvent extends Event
{
    use SerializesModels;

    public Slug $slug;

    public function __construct(Slug $slug)
    {
        $this->slug = $slug;
    }
}
