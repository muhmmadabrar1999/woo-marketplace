<?php

namespace Woo\Razorpay;

use Woo\PluginManagement\Abstracts\PluginOperationAbstract;
use Woo\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'payment_razorpay_name',
                'payment_razorpay_description',
                'payment_razorpay_key',
                'payment_razorpay_secret',
                'payment_razorpay_status',
            ])
            ->delete();
    }
}
