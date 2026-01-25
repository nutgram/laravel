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
        $this->outputComponents()->warn('This running mode is very inefficient and only suitable for development purposes. DO NOT USE IN PRODUCTION!');

        $this->info('Listening...');

        while (true) {
            $result = Process::start($this->getCommand(), $this->printOutput(...))->wait();

            if ($result->exitCode() !== 0) {
                $this->outputComponents()->error('An error occurred while running the bot. Exiting...');
                break;
            }
        }
    }

    protected function getCommand(): array
    {
        return [
            php_binary(),
            artisan_binary(),
            'nutgram:run',
            '--once',
            sprintf("--pollingTimeout=%s", $this->option('pollingTimeout')),
        ];
    }

    protected function printOutput(string $type, string $output): void
    {
        $this->output->write($output);
    }
}
