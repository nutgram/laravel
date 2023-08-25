<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\Console\MakeCommandCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:command makes a command', function () {
    $this->artisan(MakeCommandCommand::class, ['name' => 'MyCommand'])
        ->expectsOutput('Nutgram Command created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Commands/MyCommand.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyCommand');
});
