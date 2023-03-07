<?php

namespace Woo\AuditLog\Listeners;

use Woo\AuditLog\Events\AuditHandlerEvent;
use Woo\Base\Events\DeletedContentEvent;
use Exception;
use AuditLog;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            if ($event->data->id) {
                event(new AuditHandlerEvent(
                    $event->screen,
                    'deleted',
                    $event->data->id,
                    AuditLog::getReferenceName($event->screen, $event->data),
                    'danger'
                ));
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
