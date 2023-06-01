<?php

namespace Nutgram\Laravel\Mixins;

use Illuminate\Http\File as LaravelFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Client\ClientInterface;
use RuntimeException;
use SergiX44\Nutgram\Telegram\Types\Media\File;

class MixinUtils
{
    /**
     * Download a file to a Laravel disk.
     * @param  File  $file
     * @param  string  $path
     * @param  string|null  $disk
     * @param  array  $clientOpt
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function saveFileToDisk(File $file, string $path, ?string $disk = null, array $clientOpt = []): bool
    {
        $bot = $file->getBot();

        if ($bot === null) {
            throw new RuntimeException('Bot instance not found.');
        }

        $storage = Storage::disk($disk);

        if (Str::endsWith($path, ['/', '\\'])) {
            $path .= basename($file->file_path ?? $file->file_id);
        }

        if ($bot->getConfig()->is_local ?? false) {
            return $storage->put($path, $bot->downloadUrl($file));
        }

        //create temp file
        $tmpFile = tempnam(sys_get_temp_dir(), uniqid(time(), true));

        //download file to temp file
        $http = $bot->getContainer()->get(ClientInterface::class);
        $http->get($bot->downloadUrl($file), array_merge(['sink' => $tmpFile], $clientOpt));

        //save temp file to disk
        return $storage->putFileAs('/', new LaravelFile($tmpFile), $path);
    }
}
