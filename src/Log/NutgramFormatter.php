<?php

namespace Nutgram\Laravel\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;
use function Termwind\{render};

class NutgramFormatter implements FormatterInterface
{
    public function format(LogRecord $record): void
    {
        $type = $record->context['type'] ?? null;

        if ($type === 'request') {
            $this->formatRequest($record);
        } elseif ($type === 'response') {
            $this->formatResponse($record);
        }
    }

    public function formatBatch(array $records): void
    {
        array_walk($records, [$this, 'format']);
    }

    public function formatRequest(LogRecord $record): void
    {
        $content = json_encode($record->context['content'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        render(view('logging::request', [
            'time' => $record->datetime->format('Y-m-d H:i:s'),
            'message' => $record->message,
            'endpoint' => $record->context['endpoint'],
            'content' => $content,
        ]));
    }

    public function formatResponse(LogRecord $record): void
    {
        $response = json_encode($record->context['response'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        render(view('logging::response', [
            'time' => $record->datetime->format('Y-m-d H:i:s'),
            'message' => $record->message,
            'response' => $response,
        ]));
    }
}
