<?php

namespace Nutgram\Laravel\Log;

use InvalidArgumentException;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class LoggerHandler extends AbstractProcessingHandler
{
    protected string|int|null $chatId;

    public function __construct(array $config)
    {
        parent::__construct(Logger::toMonologLevel($config['level']));

        $this->chatId = $config['chat_id'];
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter("%message% %context% %extra%\n");
    }

    protected function write(LogRecord $record): void
    {
        if ($this->chatId === null) {
            throw new InvalidArgumentException('You must specify a chat_id via the NUTGRAM_LOG_CHAT_ID environment variable.');
        }

        app(Nutgram::class)->sendChunkedMessage(
            text: $this->formatText($record),
            chat_id: $this->chatId,
            parse_mode: ParseMode::HTML,
        );
    }

    protected function formatText(LogRecord $record): string
    {
        return sprintf(
            "<b>%s %s</b> (%s):\n<pre>%s</pre>",
            config('app.name'),
            $record->level->getName(),
            config('app.env'),
            $record->formatted ?? '',
        );
    }
}
