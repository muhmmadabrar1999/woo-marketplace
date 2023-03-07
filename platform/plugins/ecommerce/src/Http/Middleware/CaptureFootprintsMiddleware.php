<?php

namespace Woo\Ecommerce\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Woo\Ecommerce\Services\Footprints\TrackingFilterInterface;
use Woo\Ecommerce\Services\Footprints\TrackingLoggerInterface;

class CaptureFootprintsMiddleware
{
    protected TrackingFilterInterface $filter;

    protected TrackingLoggerInterface $logger;

    public function __construct(TrackingFilterInterface $filter, TrackingLoggerInterface $logger)
    {
        $this->filter = $filter;
        $this->logger = $logger;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->filter->shouldTrack($request)) {
            $request = $this->logger->track($request);
        }

        return $next($request);
    }
}
