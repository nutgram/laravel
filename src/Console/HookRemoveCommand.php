<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class HookRemoveCommand extends Command
{
    protected $signature = 'nutgram:hook:remove {--d|drop-pending-updates}';

    protected $description = 'Remove the bot webhook';

    public function handle(Nutgram $bot): int
    {
        $dropPendingUpdates = $this->option('drop-pending-updates');

        $bot->deleteWebhook($dropPendingUpdates);

        if ($dropPendingUpdates) {
            $this->outputComponents()->info('Pending updates dropped.');
        }

        $this->outputComponents()->info('Bot webhook removed.');

        return self::SUCCESS;
    }
}
