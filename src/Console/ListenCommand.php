<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\SingleUpdate;

class ListenCommand extends Command
{
    protected $signature = 'nutgram:listen';

    protected $description = 'Start the bot for development and reloads after every update.';

    public function handle(): void
    {
        while (true) {
            App::forgetInstance(Nutgram::class);
            $bot = App::make(Nutgram::class);
            $bot->setRunningMode(SingleUpdate::class);
            $bot->run();
        }
    }
}
