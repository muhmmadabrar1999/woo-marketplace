<?php

namespace Woo\Ecommerce\Listeners;

use Woo\Base\Events\AdminNotificationEvent;
use Woo\Base\Supports\AdminNotificationItem;
use Woo\Ecommerce\Events\OrderCreated;
use Woo\Ecommerce\Events\OrderPlacedEvent;

class OrderCreatedNotification
{
    public function handle(OrderPlacedEvent|OrderCreated $event): void
    {
        event(new AdminNotificationEvent(
            AdminNotificationItem::make()
                ->title(trans('plugins/ecommerce::order.new_order_notifications.new_order'))
                ->description(trans('plugins/ecommerce::order.new_order_notifications.description', [
                    'customer' => $event->order->user->name,
                    'quantity' => $quantity = $event->order->products->count(),
                    'product' => $quantity > 1 ? 'products' : 'product',
                ]))
                ->action(trans('plugins/ecommerce::order.new_order_notifications.view'), route('orders.edit', $event->order->id))
        ));
    }
}
