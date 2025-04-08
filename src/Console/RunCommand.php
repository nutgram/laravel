<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\PhpSubprocess;
use Symfony\Component\Process\Process;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;
use Spatie\Watcher\Watch;

class RunCommand extends Command
{
    protected $signature = 'nutgram:run {--watch : Watch for changes and restart the bot}
                                        {--without-tty : Disable output to TTY}';

    protected $description = 'Start the bot in long polling mode';

    protected ?Process $runProcess = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Nutgram $bot): int
    {
        if (!$this->option('watch')) {
            $bot->run();

            return Command::SUCCESS;
        }

        $this->info('Watching for changes...');

        if(!$this->startAsyncRun()){
            return Command::FAILURE;
        }

        $this->listenForChanges();

        return Command::SUCCESS;
    }

    protected function startAsyncRun(): bool
    {
        $this->runProcess = (new PhpSubprocess(['artisan', 'nutgram:run', '--ansi']))
            ->setTty(Process::isTtySupported() && !$this->option('without-tty'))
            ->setTimeout(null);

        $this->runProcess->start(function (string $type, string $output) {
            if(Process::isTtySupported() && !$this->option('without-tty')) {
                $this->output->write($output);
            }
        });

        sleep(1);

        return ! $this->runProcess->isTerminated();
    }

    protected function listenForChanges(): self
    {
        Watch::paths(...config('nutgram.watch_paths', []))
            ->setIntervalTime(200 * 1000)
            ->onAnyChange(function (string $event, string $path) {
                if ($this->isPhpFile($path)) {
                    $this->restartAsyncRun();
                }
            })
            ->start();

        return $this;
    }

    protected function isPhpFile(string $path): bool
    {
        return str_ends_with(strtolower($path), '.php');
    }

    protected function restartAsyncRun(): self
    {
        $this->components->info('Changes detected! Restarting bot...');

        $this->runProcess->stop();
        $this->runProcess->wait();

        $this->startAsyncRun();

        return $this;
    }
}
