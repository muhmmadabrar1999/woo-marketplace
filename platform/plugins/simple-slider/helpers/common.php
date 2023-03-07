<?php

use Woo\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Illuminate\Database\Eloquent\Collection;

if (! function_exists('get_all_simple_sliders')) {
    function get_all_simple_sliders(array $condition = []): Collection
    {
        return app(SimpleSliderInterface::class)->allBy($condition);
    }
}
