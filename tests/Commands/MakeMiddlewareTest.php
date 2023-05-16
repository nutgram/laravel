<?php

use Nutgram\Laravel\Console\MakeMiddlewareCommand;

test('nutgram:make:middleware makes a middleware', function () {
    $this->artisan(MakeMiddlewareCommand::class, ['name' => 'MyMiddleware'])
        ->expectsOutput('Nutgram Middleware created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Middleware/MyMiddleware.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyMiddleware');
});
