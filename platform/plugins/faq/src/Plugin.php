<?php

namespace Woo\Faq;

use Woo\PluginManagement\Abstracts\PluginOperationAbstract;
use Woo\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories_translations');
        Schema::dropIfExists('faqs_translations');

        Setting::query()
            ->whereIn('key', [
                'enable_faq_schema',
            ])
            ->delete();
    }
}
