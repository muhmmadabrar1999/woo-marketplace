<?php

namespace Woo\Ecommerce\Listeners;

use Woo\Base\Events\RenderingAdminWidgetEvent;
use Woo\Ecommerce\Widgets\CustomerChart;
use Woo\Ecommerce\Widgets\NewCustomerCard;
use Woo\Ecommerce\Widgets\NewOrderCard;
use Woo\Ecommerce\Widgets\OrderChart;
use Woo\Ecommerce\Widgets\NewProductCard;
use Woo\Ecommerce\Widgets\RecentOrdersTable;
use Woo\Ecommerce\Widgets\ReportGeneralHtml;
use Woo\Ecommerce\Widgets\RevenueCard;
use Woo\Ecommerce\Widgets\TopSellingProductsTable;
use Woo\Ecommerce\Widgets\TrendingProductsTable;

class RegisterEcommerceWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event->widget
            ->register([
                RevenueCard::class,
                NewProductCard::class,
                NewCustomerCard::class,
                NewOrderCard::class,
                CustomerChart::class,
                OrderChart::class,
                ReportGeneralHtml::class,
                RecentOrdersTable::class,
                TopSellingProductsTable::class,
                TrendingProductsTable::class,
            ], 'ecommerce');
    }
}
