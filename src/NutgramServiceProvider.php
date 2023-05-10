<?php

namespace Nutgram\Laravel;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Nutgram\Laravel\Console;
use Nutgram\Laravel\Mixins;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use SergiX44\Nutgram\Telegram\Types\Media\File;
use SergiX44\Nutgram\Testing\FakeNutgram;

class NutgramServiceProvider extends ServiceProvider
{
    protected const CONFIG_PATH = __DIR__.'/../config/nutgram.php';
    protected const ROUTES_PATH = __DIR__.'/../routes/telegram.php';

    public string $telegramRoutes;

    public function register()
    {
        $this->telegramRoutes = $this->app->basePath('routes/telegram.php');

        $this->mergeConfigFrom(self::CONFIG_PATH, 'nutgram');

        $this->app->singleton(Nutgram::class, function (Application $app) {
            if ($app->runningUnitTests()) {
                return Nutgram::fake();
            }

            $configuration = Configuration::fromArray([
                'container' => $app,
                'cache' => $app->get(Cache::class),
                'logger' => $app->get(LoggerInterface::class)->channel(config('nutgram.log_channel', 'null')),
                ...config('nutgram.config', []),
            ]);

            $bot = new Nutgram(config('nutgram.token') ?? FakeNutgram::TOKEN, $configuration);

            if ($app->runningInConsole()) {
                $bot->setRunningMode(Polling::class);
            } else {
                $webhook = Webhook::class;
                if (config('nutgram.safe_mode', false)) {
                    // take into account the trust proxy Laravel configuration
                    $webhook = new Webhook(fn () => $app->make('request')?->ip());
                }

                $bot->setRunningMode($webhook);
            }

            return $bot;
        });

        $this->app->alias(Nutgram::class, 'nutgram');
        $this->app->alias(Nutgram::class, FakeNutgram::class);

        $this->app->bind('bot', function (Application $app) {
            return $app->get(Nutgram::class);
        });

        if (config('nutgram.mixins', false)) {
            Nutgram::mixin(new Mixins\NutgramMixin());
            File::mixin(new Mixins\FileMixin());
        }
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/terminal', 'terminal');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\RunCommand::class,
                Console\RegisterCommandsCommand::class,
                Console\HookInfoCommand::class,
                Console\HookRemoveCommand::class,
                Console\HookSetCommand::class,
                Console\ListCommand::class,
                Console\MakeCommandCommand::class,
                Console\MakeConversationCommand::class,
                Console\MakeHandlerCommand::class,
                Console\MakeMiddlewareCommand::class,
                Console\IdeGenerateCommand::class,
                Console\LogoutCommand::class,
            ]);

            $this->publishes([
                self::CONFIG_PATH => config_path('nutgram.php'),
            ], 'nutgram');
        }

        if (config('nutgram.routes', false)) {
            $bot = $this->app->get(Nutgram::class);
            require file_exists($this->telegramRoutes) ? $this->telegramRoutes : self::ROUTES_PATH;
        }
    }
}
