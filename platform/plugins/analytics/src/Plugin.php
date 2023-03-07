<?php

namespace Woo\Analytics;

use Woo\Dashboard\Models\DashboardWidget;
use Woo\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Woo\PluginManagement\Abstracts\PluginOperationAbstract;
use Woo\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        $widgets = app(DashboardWidgetInterface::class)
            ->advancedGet([
                'condition' => [
                    [
                        'name',
                        'IN',
                        [
                            'widget_analytics_general',
                            'widget_analytics_page',
                            'widget_analytics_browser',
                            'widget_analytics_referrer',
                        ],
                    ],
                ],
            ]);

        foreach ($widgets as $widget) {
            /**
             * @var DashboardWidget $widget
             */
            $widget->delete();
        }

        Setting::query()
            ->whereIn('key', [
                'google_analytics',
                'analytics_property_id',
                'analytics_service_account_credentials',
            ])
            ->delete();
    }
}
