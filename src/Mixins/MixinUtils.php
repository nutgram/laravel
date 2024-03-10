<?php

namespace Nutgram\Laravel\Mixins;

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

        if ($bot->getConfig()->isLocal ?? false) {
            return copy(from: $bot->downloadUrl($file), to: $storage->path($path));
        }

        //create temp file
        $maxMemory = 20 * 1024 * 1024;
        $tmpFile = fopen(sprintf("php://temp/maxmemory:%d", $maxMemory), 'wb+');

        //download file to temp file
        $http = $bot->getContainer()->get(ClientInterface::class);
        $response = $http->get($bot->downloadUrl($file), array_merge(['sink' => $tmpFile], $clientOpt));

        //detach resource
        $response->getBody()->detach();

        //save temp file to disk
        $result = $storage->put($path, $tmpFile);

        //close temp file
        fclose($tmpFile);

        //return result
        return $result;
    }
}
