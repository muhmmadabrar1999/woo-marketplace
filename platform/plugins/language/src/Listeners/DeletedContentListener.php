<?php

namespace Woo\Language\Listeners;

use Woo\Base\Events\DeletedContentEvent;
use Exception;
use Language;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            Language::deleteLanguage($event->screen, $event->data);
        } catch (Exception $exception) {
            info($exception->getMessage());
        }
    }
}
