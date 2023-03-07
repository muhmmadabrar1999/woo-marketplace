<?php

namespace Database\Seeders;

use Woo\Base\Supports\BaseSeeder;
use Woo\Ecommerce\Enums\ShippingRuleTypeEnum;
use Woo\Ecommerce\Models\Shipping;
use Woo\Ecommerce\Models\ShippingRule;
use Woo\Ecommerce\Models\ShippingRuleItem;

class ShippingSeeder extends BaseSeeder
{
    public function run(): void
    {
        Shipping::truncate();
        ShippingRule::truncate();
        ShippingRuleItem::truncate();

        Shipping::create(['title' => 'All']);

        ShippingRule::create([
            'name' => 'Free delivery',
            'shipping_id' => 1,
            'type' => ShippingRuleTypeEnum::BASED_ON_PRICE,
            'from' => 0,
            'to' => null,
            'price' => 0,
        ]);
    }
}
