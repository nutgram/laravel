<?php

use Mockery\MockInterface;
use Nutgram\Laravel\Console\LogoutCommand;
use SergiX44\Nutgram\Nutgram;

test('nutgram:logout makes a logout', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $mock->shouldReceive('deleteWebhook')->andReturn(true);
        $mock->shouldReceive('close')->andReturn(true);
        $mock->shouldReceive('logout')->andReturn(true);
    });

    $this->artisan(LogoutCommand::class)
        ->expectsOutputToContain('Webhook deleted.')
        ->expectsOutputToContain('Bot closed.')
        ->expectsOutputToContain('Logged out.')
        ->expectsOutputToContain('Done.')
        ->expectsOutputToContain('Remember to set the webhook again if needed!')
        ->assertSuccessful();
});

test('nutgram:logout makes a logout + drop pending updates', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $mock->shouldReceive('deleteWebhook')->with(['drop_pending_updates' => true])->andReturn(true);
        $mock->shouldReceive('close')->andReturn(true);
        $mock->shouldReceive('logout')->andReturn(true);
    });

    $this->artisan(LogoutCommand::class, ['--drop-pending-updates' => true])
        ->expectsOutputToContain('Webhook deleted.')
        ->expectsOutputToContain('Bot closed.')
        ->expectsOutputToContain('Logged out.')
        ->expectsOutputToContain('Done.')
        ->expectsOutputToContain('Remember to set the webhook again if needed!')
        ->assertSuccessful();
});
