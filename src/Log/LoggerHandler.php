<?php

namespace Nutgram\Laravel\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use SergiX44\Nutgram\Nutgram;

class LoggerHandler extends AbstractProcessingHandler
{
    protected Nutgram $bot;

    protected string|int $chatId;

    public function __construct(array $config)
    {
        parent::__construct(Logger::toMonologLevel($config['level']), true);

        $this->bot = app(Nutgram::class);
        $this->chatId = $config['chat_id'];
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter("%message% %context% %extra%\n");
    }

    protected function write(LogRecord $record): void
    {
        $this->bot->sendChunkedMessage(
            text: $this->formatText($record),
            chat_id: $this->chatId,
            parse_mode: 'html',
        );
    }

    protected function formatText(LogRecord $record): string
    {
        return sprintf(
            "<b>%s %s</b> (%s):\n<pre>%s</pre>",
            config('app.name'),
            $record['level_name'],
            config('app.env'),
            $record['formatted']
        );
    }
}
