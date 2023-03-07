<?php

namespace Woo\SeoHelper\Listeners;

use Woo\Base\Events\DeletedContentEvent;
use Exception;
use SeoHelper;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            SeoHelper::deleteMetaData($event->screen, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
