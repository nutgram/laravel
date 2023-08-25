<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\Console\MakeMiddlewareCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:middleware makes a middleware', function () {
    $this->artisan(MakeMiddlewareCommand::class, ['name' => 'MyMiddleware'])
        ->expectsOutput('Nutgram Middleware created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Middleware/MyMiddleware.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyMiddleware');
});
