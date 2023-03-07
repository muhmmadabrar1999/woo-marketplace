<?php

namespace Woo\SslCommerz;

use Woo\PluginManagement\Abstracts\PluginOperationAbstract;
use Woo\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'payment_sslcommerz_name',
                'payment_sslcommerz_description',
                'payment_sslcommerz_store_id',
                'payment_sslcommerz_store_password',
                'payment_sslcommerz_mode',
                'payment_sslcommerz_status',
            ])
            ->delete();
    }
}
