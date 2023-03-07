<?php

namespace Woo\Menu\Listeners;

use Woo\Base\Events\DeletedContentEvent;
use Woo\Menu\Repositories\Interfaces\MenuNodeInterface;
use Exception;
use Menu;

class DeleteMenuNodeListener
{
    protected MenuNodeInterface $menuNodeRepository;

    public function __construct(MenuNodeInterface $menuNodeRepository)
    {
        $this->menuNodeRepository = $menuNodeRepository;
    }

    public function handle(DeletedContentEvent $event): void
    {
        if (in_array(get_class($event->data), Menu::getMenuOptionModels())) {
            try {
                $this->menuNodeRepository->deleteBy([
                    'reference_id' => $event->data->id,
                    'reference_type' => get_class($event->data),
                ]);
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }
}
