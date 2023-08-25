<?php

use Mockery\MockInterface;
use Nutgram\Laravel\Console\HookInfoCommand;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Common\WebhookInfo;

test('nutgram:hook:info prints the webhook info', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $webhookInfo = Nutgram::fake()->getContainer()->get(WebhookInfo::class);
        $webhookInfo->url = '';
        $webhookInfo->has_custom_certificate = false;
        $webhookInfo->pending_update_count = 0;
        $webhookInfo->ip_address = null;
        $webhookInfo->last_error_date = null;
        $webhookInfo->last_error_message = null;
        $webhookInfo->last_synchronization_error_date = null;
        $webhookInfo->max_connections = null;
        $webhookInfo->allowed_updates = null;

        $mock->shouldReceive('getWebhookInfo')->andReturn($webhookInfo);
    });

    $this->artisan(HookInfoCommand::class)
        ->assertSuccessful();
});

test('nutgram:hook:info prints the webhook info with error', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $webhookInfo = Nutgram::fake()->getContainer()->get(WebhookInfo::class);
        $webhookInfo->url = '';
        $webhookInfo->has_custom_certificate = false;
        $webhookInfo->pending_update_count = 1;
        $webhookInfo->ip_address = '1.2.3.4';
        $webhookInfo->last_error_date = 1647554568;
        $webhookInfo->last_error_message = 'foobar';
        $webhookInfo->last_synchronization_error_date = 1647554568;
        $webhookInfo->max_connections = 50;
        $webhookInfo->allowed_updates = null;

        $mock->shouldReceive('getWebhookInfo')->andReturn($webhookInfo);
    });

    $this->artisan(HookInfoCommand::class)
        ->assertSuccessful();
});

test('nutgram:hook:info does not print the webhook info', function () {
    $this->mock(Nutgram::class, function (MockInterface $mock) {
        $mock->shouldReceive('getWebhookInfo')->andReturn(null);
    });

    $this->artisan(HookInfoCommand::class)
        ->assertFailed();
});
