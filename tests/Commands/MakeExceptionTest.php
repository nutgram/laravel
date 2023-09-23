<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\Console\MakeExceptionCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:exception makes an exception', function () {
    $this->artisan(MakeExceptionCommand::class, ['name' => 'MyException'])
        ->expectsOutput('Nutgram Exception created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Exceptions/MyException.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyException');
});
