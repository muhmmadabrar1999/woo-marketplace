<?php

use Woo\Marketplace\Models\Store;
use Woo\Slug\Models\Slug;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        try {
            Slug::where('reference_type', Store::class)->update(['prefix' => SlugHelper::getPrefix(Store::class)]);
        } catch (Throwable) {}
    }
};
