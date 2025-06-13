<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use function Illuminate\Support\artisan_binary;
use function Illuminate\Support\php_binary;

class ListenCommand extends Command
{
    protected $signature = 'nutgram:listen {--pollingTimeout=5}';

    protected $description = 'Start the bot for development and reloads after every update.';

    public function handle(): void
    {
        $this->warn('This running mode is very inefficient and only suitable for development purposes. DO NOT USE IN PRODUCTION!');
        $this->info('Listening...');
        while (true) {
            $result = Process::start([
                php_binary(), artisan_binary(), 'nutgram:run', '--once',
                '--pollingTimeout='.$this->option('pollingTimeout'),
            ], function (string $type, string $output) {
                $this->output->write($output);
            })->wait();
            if ($result->exitCode() !== 0) {
                $this->error('An error occurred while running the bot. Exiting...');
                break;
            }
        }
    }
}
