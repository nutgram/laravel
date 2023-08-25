<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nutgram\Laravel\Console\MakeCommandCommand;
use Nutgram\Laravel\Console\MakeConversationCommand;
use Nutgram\Laravel\Console\MakeHandlerCommand;
use Nutgram\Laravel\Console\MakeMiddlewareCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:x fails due to existing file', function (string $command, string $folder, string $file) {
    $fileName = 'MyCommand';
    $filePath = sprintf("%s%s%s%s%s.php",
        config('nutgram.namespace'),
        DIRECTORY_SEPARATOR,
        $folder,
        DIRECTORY_SEPARATOR,
        $fileName
    );
    $relativePath = Str::after($filePath, base_path());

    $this->artisan($command, ['name' => $fileName]);

    $this->artisan($command, ['name' => $fileName])
        ->expectsOutput(sprintf("%s already exists.", $relativePath))
        ->assertExitCode(1);
})->with([
    'commands' => [MakeCommandCommand::class, 'Commands', 'MyCommand'],
    'conversations' => [MakeConversationCommand::class, 'Conversations', 'MyConversation'],
    'handlers' => [MakeHandlerCommand::class, 'Handlers', 'MyHandler'],
    'middleware' => [MakeMiddlewareCommand::class, 'Middleware', 'MyMiddleware'],
]);
