<?php

namespace Nutgram\Laravel\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JsonException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;
use function Laravel\Prompts\table;

class HookInfoCommand extends Command
{
    protected $signature = 'nutgram:hook:info';

    protected $description = 'Get current webhook status';

    /**
     * @throws TelegramException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle(Nutgram $bot): int
    {
        $this->newLine();

        $webhookInfo = $bot->getWebhookInfo();

        if ($webhookInfo === null) {
            $this->outputComponents()->error('Unable to get webhook info');
            return self::FAILURE;
        }

        $lastErrorDate = $this->getParsedDate($webhookInfo->last_error_date);
        $lastSynchronizationErrorDate = $this->getParsedDate($webhookInfo->last_synchronization_error_date);
        $allowedUpdates = $webhookInfo->allowed_updates ?: [];

        $this->outputComponents()->twoColumnDetail('<fg=green;options=bold>Webhook Info</>');
        $this->outputComponents()->twoColumnDetail('url', $webhookInfo->url);
        $this->outputComponents()->twoColumnDetail(
            first: 'has_custom_certificate',
            second: $this->boolToConsoleString($webhookInfo->has_custom_certificate),
        );
        $this->outputComponents()->twoColumnDetail('pending_update_count', $webhookInfo->pending_update_count);
        $this->outputComponents()->twoColumnDetail('ip_address', $webhookInfo->ip_address);
        $this->outputComponents()->twoColumnDetail('last_error_date', $lastErrorDate);
        $this->outputComponents()->twoColumnDetail('last_error_message', $webhookInfo->last_error_message);
        $this->outputComponents()->twoColumnDetail('last_synchronization_error_date', $lastSynchronizationErrorDate);
        $this->outputComponents()->twoColumnDetail('max_connections', $webhookInfo->max_connections);
        $this->outputComponents()->twoColumnDetail('allowed_updates', count($allowedUpdates));

        $this->newLine();
        $this->outputComponents()->twoColumnDetail('<fg=green;options=bold>Allowed Updates</>');
        table(rows: collect($allowedUpdates)->chunk(5)->toArray());

        $this->newLine();

        return self::SUCCESS;
    }

    protected function getParsedDate(?string $date): ?string
    {
        return $date !== null ? date('Y-m-d H:i:s', $date).' UTC' : null;
    }

    protected function boolToConsoleString(bool $value): string
    {
        return $value ? '<fg=green;options=bold>TRUE</>' : '<fg=yellow;options=bold>FALSE</>';
    }
}
