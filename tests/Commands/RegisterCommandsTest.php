<?php

use Nutgram\Laravel\Console\RegisterCommandsCommand;
use SergiX44\Nutgram\Nutgram;

test('nutgram:register-commands registers the bot commands', function () {
    $bot = Nutgram::fake();

    $bot->onCommand('start', static function () {
    })->description('start command');

    $bot->onCommand('help', static function () {
    })->description('help command');

    $this->artisan(RegisterCommandsCommand::class)
        ->expectsOutput('Bot commands set.')
        ->assertSuccessful();
});
