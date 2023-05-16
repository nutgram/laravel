<?php

use Nutgram\Laravel\Console\MakeCommandCommand;

test('nutgram:make:command makes a command', function () {
    $this->artisan(MakeCommandCommand::class, ['name' => 'MyCommand'])
        ->expectsOutput('Nutgram Command created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Commands/MyCommand.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyCommand');
});
