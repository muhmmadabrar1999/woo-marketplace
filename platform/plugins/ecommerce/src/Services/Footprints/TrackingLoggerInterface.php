<?php

namespace Woo\Ecommerce\Services\Footprints;

use Illuminate\Http\Request;

interface TrackingLoggerInterface
{
    public function track(Request $request): Request;
}
