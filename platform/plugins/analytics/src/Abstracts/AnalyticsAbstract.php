<?php

namespace Woo\Analytics\Abstracts;

use Woo\Analytics\Period;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

abstract class AnalyticsAbstract
{
    use Macroable;

    public ?string $propertyId = null;
    public ?string $credentials = null;

    public function getPropertyId(): string
    {
        return $this->propertyId;
    }

    public function setPropertyId(string $propertyId): static
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    abstract public function fetchMostVisitedPages(Period $period, int $maxResults = 20): Collection;

    abstract public function fetchTopReferrers(Period $period, int $maxResults = 20): Collection;

    abstract public function fetchTopBrowsers(Period $period, int $maxResults = 10): Collection;
}
