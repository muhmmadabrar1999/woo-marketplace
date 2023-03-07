<?php

namespace Woo\Base\Supports;

use BaseHelper;
use Woo\Base\Events\FinishedSeederEvent;
use Woo\Media\Models\MediaFile;
use Woo\Media\Models\MediaFolder;
use Woo\PluginManagement\Services\PluginService;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;
use RvMedia;
use Setting;

class BaseSeeder extends Seeder
{
    public function uploadFiles(string $folder, ?string $basePath = null): array
    {
        Storage::deleteDirectory($folder);
        MediaFile::where('url', 'LIKE', $folder . '/%')->forceDelete();
        MediaFolder::where('name', $folder)->forceDelete();

        $mimeType = new MimeTypes();

        $files = [];

        $folderPath = ($basePath ?: database_path('seeders/files')) . '/' . $folder;

        if (! File::isDirectory($folderPath)) {
            return [];
        }

        foreach (File::allFiles($folderPath) as $file) {
            $type = $mimeType->getMimeType(File::extension($file));
            $files[] = RvMedia::uploadFromPath($file, 0, $folder, $type);
        }

        return $files;
    }

    public function activateAllPlugins(): array
    {
        try {
            $plugins = array_values(BaseHelper::scanFolder(plugin_path()));

            $pluginService = app(PluginService::class);

            foreach ($plugins as $plugin) {
                $pluginService->activate($plugin);
            }

            return $plugins;
        } catch (Exception) {
            return [];
        }
    }

    public function prepareRun(): array
    {
        Setting::forgetAll();

        return $this->activateAllPlugins();
    }

    protected function random(int $from, int $to, array $exceptions = []): int
    {
        sort($exceptions); // lets us use break; in the foreach reliably
        $number = rand($from, $to - count($exceptions)); // or mt_rand()

        foreach ($exceptions as $exception) {
            if ($number >= $exception) {
                $number++; // make up for the gap
            } else { /*if ($number < $exception)*/
                break;
            }
        }

        return $number;
    }

    protected function finished(): void
    {
        event(new FinishedSeederEvent());
    }
}
