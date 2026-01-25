<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class LogoutCommand extends Command
{
    protected $signature = 'nutgram:logout {--d|drop-pending-updates}';

    protected $description = 'Log out from the cloud Bot API server';

    public function handle(Nutgram $bot): int
    {
        $dropPendingUpdates = (bool)$this->option('drop-pending-updates');

        try {
            $bot->deleteWebhook($dropPendingUpdates);
        } finally {
            $this->outputComponents()->info('Webhook deleted.');
        }

        try {
            $bot->close();
        } finally {
            $this->outputComponents()->info('Bot closed.');
        }

        try {
            $bot->logOut();
        } finally {
            $this->outputComponents()->info('Logged out.');
        }

        $this->outputComponents()->success('Done.');
        $this->outputComponents()->warn('Remember to set the webhook again if needed!');

        return self::SUCCESS;
    }
}
