<?php

namespace Woo\AuditLog\Listeners;

use Woo\AuditLog\Events\AuditHandlerEvent;
use Woo\Base\Events\CreatedContentEvent;
use Exception;
use AuditLog;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            if ($event->data->id) {
                event(new AuditHandlerEvent(
                    $event->screen,
                    'created',
                    $event->data->id,
                    AuditLog::getReferenceName($event->screen, $event->data),
                    'info'
                ));
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
