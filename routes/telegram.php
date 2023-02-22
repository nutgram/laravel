<?php

use Nutgram\Laravel\Facades\Bot;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

Bot::onCommand('start', function (Nutgram $bot) {
    return $bot->sendMessage('Hello, world!');
})->description('The start command!');
