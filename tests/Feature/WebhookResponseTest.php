<?php

use Illuminate\Support\Facades\Route;
use Nutgram\Laravel\Middleware\SendWebhookResponse;
use Nutgram\Laravel\RunningMode\LaravelWebhook;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Testing\FakeNutgram;

beforeEach(function () {
    /** @var FakeNutgram $bot */
    $this->bot = app(Nutgram::class);
    $this->bot->setRunningMode(LaravelWebhook::class);

    $webhook = function (Nutgram $bot) {
        $bot->run();

        return 'ok';
    };
    Route::post('/hook', $webhook)->middleware(SendWebhookResponse::class);
    Route::post('/hook-without-middleware', $webhook);

    $this->update = [
        'update_id' => 1,
        'message' => [
            'message_id' => 1,
            'date' => time(),
            'chat' => ['id' => 123, 'type' => 'private'],
            'from' => ['id' => 123, 'is_bot' => false, 'first_name' => 'Tony'],
            'text' => 'Ciao',
        ],
    ];
});

it('sends the asResponse request as the webhook response', function () {
    $this->expectOutputString('');
    $this->bot->onText('Ciao', fn (Nutgram $bot) => $bot->asResponse()->sendMessage('Hello'));

    $this->postJson('/hook', $this->update)
        ->assertOk()
        ->assertExactJson(['method' => 'sendMessage', 'chat_id' => 123, 'text' => 'Hello']);

    $this->bot->assertNoReply();
});

it('sends a normal request when the route has no SendWebhookResponse middleware', function () {
    $this->expectOutputString('');
    $this->bot->onText('Ciao', fn (Nutgram $bot) => $bot->asResponse()->sendMessage('Hello'));

    $this->postJson('/hook-without-middleware', $this->update)
        ->assertOk()
        ->assertContent('ok');

    $this->bot->assertReply('sendMessage', ['chat_id' => 123, 'text' => 'Hello']);
});

it('sends only the first asResponse request as the webhook response', function () {
    $this->bot->onText('Ciao', function (Nutgram $bot) {
        $bot->asResponse()->sendMessage('Hello');
        $bot->asResponse()->sendMessage('World');
    });

    $this->postJson('/hook', $this->update)
        ->assertOk()
        ->assertExactJson(['method' => 'sendMessage', 'chat_id' => 123, 'text' => 'Hello']);

    $this->bot->assertCalled('sendMessage')->assertReply('sendMessage', ['text' => 'World']);
});

it('keeps the route response when nothing is sent as the webhook response', function () {
    $this->bot->onText('Ciao', fn (Nutgram $bot) => $bot->sendMessage('Hello'));

    $this->postJson('/hook', $this->update)
        ->assertOk()
        ->assertContent('ok');

    $this->bot->assertReply('sendMessage', ['text' => 'Hello']);
});
