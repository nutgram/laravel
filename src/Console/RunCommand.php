<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Process\InvokedProcess;
use Illuminate\Support\Facades\Process;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use Spatie\Watcher\Watch;

class RunCommand extends Command
{
    protected $signature = 'nutgram:run {--watch : Watch for changes and restart the bot}';

    protected $description = 'Start the bot in long polling mode';

    protected ?InvokedProcess $watchProcess = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Nutgram $bot): void
    {
        if ($this->option('watch')) {
            $this->watch();

            return;
        }

        $bot->run();
    }

    protected function watch(): void
    {
        $this->info('Watching for changes...');
        $this->startWatchProcess();

        Watch::paths(...config('nutgram.watch.paths', []))
            ->setIntervalTime(config('nutgram.watch.interval', 250 * 1000))
            ->onAnyChange(function () {
                $this->warn('Restarting the bot...');
                $this->watchProcess?->stop();
                $this->startWatchProcess();
            })
            ->start();
    }

    protected function startWatchProcess(): void
    {
        $this->watchProcess = Process::start(
            command: config('nutgram.watch.bin', PHP_BINARY).' artisan nutgram:run',
            output: function (string $type, string $output) {
                $this->output->write($output);
            }
        );

        $this->output->write($this->watchProcess->output());
    }
}
