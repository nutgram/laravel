<?php

use Nutgram\Laravel\Console\IdeGenerateCommand;

test('nutgram:ide:generate publish the stub file', function () {
    $this->artisan(IdeGenerateCommand::class)
        ->expectsOutput('Generating IDE helper...')
        ->expectsOutput('Done!')
        ->assertExitCode(0);

    expect(base_path('_ide_helper_nutgram.php'))
        ->toBeFile()
        ->getFileContent()
        ->toContain('namespace SergiX44\Nutgram\Telegram\Types\Media {');
});
