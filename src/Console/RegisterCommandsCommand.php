<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use SergiX44\Nutgram\Nutgram;

class RegisterCommandsCommand extends Command
{
    protected $signature = 'nutgram:register-commands';

    protected $description = 'Register the bot commands';

    public function handle(Nutgram $bot): int
    {
        $bot->registerMyCommands();

        $this->info('Bot commands set.');

        return 0;
    }
}
