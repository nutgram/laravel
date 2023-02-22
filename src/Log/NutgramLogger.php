<?php

namespace Nutgram\Laravel\Log;

use Monolog\Logger;

class NutgramLogger
{
    public function __invoke(array $config)
    {
        return new Logger(
            name: config('app.name'),
            handlers: [new LoggerHandler($config)]
        );
    }
}
