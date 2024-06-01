<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\SingleUpdate;

class ListenCommand extends Command
{
    protected $signature = 'nutgram:listen';

    protected $description = 'Start the bot for development and reloads after every update.';

    public function handle(): void
    {
        $this->info('Listening...');
        while (true) {
            app()->forgetInstance(Nutgram::class);
            $bot = app(Nutgram::class);
            $bot->setRunningMode(SingleUpdate::class);
            $bot->run();
        }
    }
}
