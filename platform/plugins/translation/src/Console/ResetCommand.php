<?php

namespace Woo\Translation\Console;

use Illuminate\Console\Command;
use Woo\Translation\Manager;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:translations:reset', 'Delete all languages records in database')]
class ResetCommand extends Command
{
    public function handle(Manager $manager): int
    {
        $manager->truncateTranslations();
        $this->components->info('All translations are deleted');

        return self::SUCCESS;
    }
}
