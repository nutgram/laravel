<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\SingleUpdate;

class RunCommand extends Command
{
    protected $signature = 'nutgram:run {--once} {--pollingTimeout=}';

    protected $description = 'Start the bot in long polling mode';

    public function handle(Nutgram $bot): void
    {
        if ($pollingTimeout = $this->option('pollingTimeout')) {
            config()?->set('nutgram.config.polling.timeout', (int)$pollingTimeout);
        }

        if ($this->option('once')) {
            $bot->setRunningMode(SingleUpdate::class);
        }

        $bot->run();
    }
}
