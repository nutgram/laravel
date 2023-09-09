<?php

namespace Nutgram\Laravel\Console;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use JsonException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;

class HookSetCommand extends Command
{
    protected $signature = 'nutgram:hook:set {url} {--ip=} {--max-connections=50}';

    protected $description = 'Set the bot webhook';

    /**
     * @throws TelegramException
     * @throws GuzzleException
     * @throws JsonException
     */
    public function handle(Nutgram $bot): int
    {
        /** @var string $url */
        $url = $this->argument('url');

        /** @var ?string $ip_address */
        $ip_address = $this->option('ip') ?: null;

        /** @var ?string $max_connections */
        $max_connections = $this->option('max-connections') ?: null;

        //cast to int if not null
        if (is_numeric($max_connections)) {
            $max_connections = (int)$max_connections;
        }

        $bot->setWebhook(
            url: $url,
            ip_address: $ip_address,
            max_connections: $max_connections,
            secret_token: config('nutgram.safe_mode', false) ? md5(config('app.key')) : null
        );

        $this->info("Bot webhook set with url: $url");

        return 0;
    }
}
