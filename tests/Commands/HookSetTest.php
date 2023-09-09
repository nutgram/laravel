<?php

use Mockery\MockInterface;
use Nutgram\Laravel\Console\HookSetCommand;
use SergiX44\Nutgram\Nutgram;

test('nutgram:hook:set sets the bot webhook', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $mock->shouldReceive('setWebhook')
            ->with('https://foo.bar/hook', null, null, 50, null, null, null)
            ->andReturn(0);
    });

    $this->artisan(HookSetCommand::class, ['url' => 'https://foo.bar/hook'])
        ->expectsOutputToContain('Bot webhook set with url: https://foo.bar/hook')
        ->assertSuccessful();
});

test('nutgram:hook:set sets the bot webhook + safe mode', function () {
    $appKey = 'test-key';
    $hashedAppKey = md5($appKey);

    config([
        'app.key' => $appKey,
        'nutgram.safe_mode' => true,
    ]);

    $this->mock(Nutgram::class, function (MockInterface $mock) use ($hashedAppKey) {
        $mock->shouldReceive('setWebhook')
            ->with('https://foo.bar/hook', null, null, 50, null, null, $hashedAppKey)
            ->andReturn(0);
    });

    $this->artisan(HookSetCommand::class, ['url' => 'https://foo.bar/hook'])
        ->expectsOutputToContain('Bot webhook set with url: https://foo.bar/hook')
        ->assertSuccessful();
});
