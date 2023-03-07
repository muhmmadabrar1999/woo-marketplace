<?php

namespace Woo\Ecommerce\Widgets;

use Woo\Base\Widgets\Table;

class RecentOrdersTable extends Table
{
    protected string $table = \Woo\Ecommerce\Tables\Reports\RecentOrdersTable::class;

    protected string $route = 'ecommerce.report.recent-orders';

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::reports.recent_orders');
    }
}
