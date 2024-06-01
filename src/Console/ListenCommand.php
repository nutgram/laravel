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
        $this->info('Listening...');
        while (true) {
            $app = App::configure(base_path())->create();
            $bot = $app->make(Nutgram::class);
            $bot->setRunningMode(SingleUpdate::class);
            $bot->run();
        }
    }
}
