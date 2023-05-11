<?php

use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Testing\FakeNutgram;

it('can get the facade', function () {
    Telegram::fake();
    expect(Telegram::getFacadeRoot())->toBeInstanceOf(FakeNutgram::class);
});

it('can sendMessage with facade', function () {
    Telegram::fake();
    Telegram::sendMessage('hello');
    Telegram::assertReplyText('hello');
});

it('can register handler and run bot with facade', function () {
    Telegram::fake();

    Telegram::onCommand('start', function (Nutgram $bot) {
        $bot->sendMessage('hello');
    });

    Telegram::hearText('/start')
        ->reply()
        ->assertReplyText('hello');
});

it('can group better with facade', function () {
    Telegram::fake();
    $test = '';

    $middleware0 = function (Nutgram $bot, $next) use (&$test) {
        $test .= '-[MW0]';
        $next($bot);
    };

    $middleware1 = function (Nutgram $bot, $next) use (&$test) {
        $test .= '[MW1]';
        $next($bot);
    };

    Telegram::middleware($middleware0);

    Telegram::group(function () use (&$test) {
        Telegram::onMessage(function (Nutgram $bot) use (&$test) {
            $test .= 'H1';
        })->middleware(function (Nutgram $bot, $next) use (&$test) {
            $test .= 'LM1';
            $next($bot);
        });
    })->middleware($middleware1);

    Telegram::onMessage(function (Nutgram $bot) use (&$test) {
        $test .= 'H2';
    })->middleware(function (Nutgram $bot, $next) use (&$test) {
        $test .= 'LM2';
        $next($bot);
    });

    Telegram::hearText('hello')->reply();

    expect($test)->toBe('-[MW0]LM2H2-[MW0][MW1]LM1H1');
});
