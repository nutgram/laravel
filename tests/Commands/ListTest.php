<?php

use Nutgram\Laravel\Console\ListCommand;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\MessageType;

test('nutgram:list with no handlers registered', function () {
    $this->swap(Nutgram::class, Nutgram::fake());

    $this
        ->artisan(ListCommand::class)
        ->expectsOutputToContain("Your application doesn't have any handlers.")
        ->assertFailed();
});

test('nutgram:list with handler registered', function () {
    $bot = Nutgram::fake();

    $callback = new class {
        public function foo(Nutgram $bot)
        {
        }

        public function bar(Nutgram $bot)
        {
        }
    };

    $bot->onCommand('start', static function () {
    });
    $bot->onText('foo', [$callback, 'foo']);
    $bot->onText('bar', [$callback, 'bar']);
    $bot->onMessage([$callback::class, 'foo']);
    $bot->onMessageType(MessageType::PHOTO, static function () {
    });
    $bot->onEditedMessage(static function () {
    });
    $bot->onChannelPost(static function () {
    });
    $bot->onEditedChannelPost(static function () {
    });
    $bot->onInlineQuery(static function () {
    });
    $bot->onchosenInlineResult(static function () {
    });
    $bot->onCallbackQuery(static function () {
    });
    $bot->onShippingQuery(static function () {
    });
    $bot->onPreCheckoutQuery(static function () {
    });
    $bot->onMessagePoll(static function () {
    });
    $bot->onPollAnswer(static function () {
    });
    $bot->onMyChatMember(static function () {
    });
    $bot->onChatMember(static function () {
    });
    $bot->onChatJoinRequest(static function () {
    });
    $bot->onApiError(static function () {
    });
    $bot->onException(static function () {
    });
    $bot->onSticker(static function () {
    });

    $this->swap(Nutgram::class, $bot);

    $this
        ->artisan(ListCommand::class)
        ->doesntExpectOutput('No handlers have been registered.')
        ->assertSuccessful();
});

test('nutgram:list with --json flag', function(){
    $bot = Nutgram::fake();

    $bot->onCommand('start', static function () {});

    $jsonOutput = [
        ['handler' => 'onCommand', 'pattern' => '/start', 'callable' => 'closure'],
    ];

    $this
        ->artisan(ListCommand::class, ['--json' => true])
        ->expectsOutputToContain(json_encode($jsonOutput))
        ->assertSuccessful();
});
