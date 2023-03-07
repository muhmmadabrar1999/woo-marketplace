<?php

namespace Woo\Marketplace\Listeners;

use Woo\Base\Events\AdminNotificationEvent;
use Woo\Base\Supports\AdminNotificationItem;
use Woo\Marketplace\Events\WithdrawalRequested;

class WithdrawalRequestedNotification
{
    public function handle(WithdrawalRequested $event): void
    {
        event(new AdminNotificationEvent(
            AdminNotificationItem::make()
                ->title(trans('plugins/marketplace::withdrawal.new_withdrawal_request_notifications.new_withdrawal_request'))
                ->description(trans('plugins/marketplace::withdrawal.new_withdrawal_request_notifications.description', [
                    'customer' => $event->customer->name,
                ]))
                ->action(trans('plugins/marketplace::withdrawal.new_withdrawal_request_notifications.view'), route('marketplace.withdrawal.edit', $event->withdrawal->id))
        ));
    }
}
