<?php

namespace Woo\Location\Events;

use Woo\Base\Events\Event;
use Woo\Location\Models\City;
use Illuminate\Queue\SerializesModels;

class ImportedCityEvent extends Event
{
    use SerializesModels;

    public array $row = [];

    public City $city;

    public function __construct(array $row, City $city)
    {
        $this->row = $row;
        $this->city = $city;
    }
}
