<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class HookSetCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'nutgram:hook:set {url} {--ip=} {--max-connections=50}';

    protected $description = 'Set the bot webhook';

    public function handle(Nutgram $bot): int
    {
        /** @var string $url */
        $url = $this->argument('url');

        try {
            $bot->setWebhook(
                url: $url,
                ip_address: $this->option('ip') ?: null,
                max_connections: $this->getMaxConnections(),
                secret_token: $this->getSecretToken(),
            );
        } catch (TelegramException $e) {
            $this->outputComponents()->error($e->getMessage());

            return self::FAILURE;
        }

        $this->outputComponents()->success('Bot webhook set.');

        return self::SUCCESS;
    }

    protected function getMaxConnections(): ?int
    {
        /** @var ?string $max_connections */
        $max_connections = $this->option('max-connections') ?: null;

        return is_numeric($max_connections) ? (int)$max_connections : null;
    }

    protected function getSecretToken(): ?string
    {
        return config('nutgram.safe_mode', false) ? md5(config('app.key')) : null;
    }
}
