<?php

namespace Woo\Dashboard\Repositories\Caches;

use Woo\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class DashboardWidgetSettingCacheDecorator extends CacheAbstractDecorator implements DashboardWidgetSettingInterface
{
    /**
     * {@inheritDoc}
     */
    public function getListWidget()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
