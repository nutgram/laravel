<?php

use Nutgram\Laravel\Console\MakeHandlerCommand;

test('nutgram:make:handler makes an handler', function () {
    $this->artisan(MakeHandlerCommand::class, ['name' => 'MyHandler'])
        ->expectsOutput('Nutgram Handler created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Handlers/MyHandler.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyHandler');
});
