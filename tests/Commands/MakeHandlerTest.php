<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\Console\MakeHandlerCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:handler makes an handler', function () {
    $this->artisan(MakeHandlerCommand::class, ['name' => 'MyHandler'])
        ->expectsOutput('Nutgram Handler created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Handlers/MyHandler.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyHandler');
});
