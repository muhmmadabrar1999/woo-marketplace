<?php

namespace Woo\Faq\Listeners;

use Woo\Base\Events\DeletedContentEvent;
use Exception;
use MetaBox;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            MetaBox::deleteMetaData($event->data, 'faq_schema_config');
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
