<?php

namespace Nutgram\Laravel\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JsonException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class HookRemoveCommand extends Command
{
    protected $signature = 'nutgram:hook:remove {--d|drop-pending-updates}';

    protected $description = 'Remove the bot webhook';

    /**
     * @throws TelegramException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle(Nutgram $bot): int
    {
        $dropPendingUpdates = $this->option('drop-pending-updates');

        $bot->deleteWebhook($dropPendingUpdates);

        if ($dropPendingUpdates) {
            $this->info('Pending updates dropped.');
        }
        $this->info('Bot webhook removed.');

        return 0;
    }
}
