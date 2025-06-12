<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\SingleUpdate;

class ListenCommand extends Command
{
    protected $signature = 'nutgram:listen {--pollingTimeout=5}';

    protected $description = 'Start the bot for development and reloads after every update.';

    public function handle(): void
    {
        $this->warn('This running mode is very inefficient and only suitable for development purposes. DO NOT USE IN PRODUCTION!');
        $this->info('Listening...');
        config()?->set('nutgram.config.polling.timeout', $this->option('pollingTimeout'));
        while (true) {
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            app()->forgetInstance(Nutgram::class);
            $bot = app(Nutgram::class);
            $bot->setRunningMode(SingleUpdate::class);
            $bot->run();
        }
    }
}
