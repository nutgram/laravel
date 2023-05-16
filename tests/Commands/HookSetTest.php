<?php

use Mockery\MockInterface;
use Nutgram\Laravel\Console\HookSetCommand;
use SergiX44\Nutgram\Nutgram;

test('nutgram:hook:set sets the bot webhook', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $mock->shouldReceive('setWebhook')
            ->with('https://foo.bar/hook', null, null, 50)
            ->andReturn(0);
    });

    $this->artisan(HookSetCommand::class, ['url' => 'https://foo.bar/hook'])
        ->expectsOutputToContain('Bot webhook set with url: https://foo.bar/hook')
        ->assertSuccessful();
});
