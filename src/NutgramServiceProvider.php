<?php

namespace Nutgram\Laravel;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Nutgram\Laravel\RunningMode\LaravelWebhook;
use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
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
            $configuration = new Configuration(
                apiUrl: config('nutgram.config.api_url', Configuration::DEFAULT_API_URL),
                botId: config('nutgram.config.bot_id'),
                botName: config('nutgram.config.bot_name'),
                testEnv: config('nutgram.config.test_env', false),
                isLocal: config('nutgram.config.is_local', false),
                clientTimeout: config('nutgram.config.timeout', Configuration::DEFAULT_CLIENT_TIMEOUT),
                clientOptions: config('nutgram.config.client', []),
                container: $app,
                hydrator: config('nutgram.config.hydrator', Configuration::DEFAULT_HYDRATOR),
                cache: $app->get(Cache::class),
                logger: $app->get(LoggerInterface::class)->channel(config('nutgram.log_channel', 'null')),
                localPathTransformer: config('nutgram.config.local_path_transformer'),
                pollingTimeout: config('nutgram.config.polling.timeout', Configuration::DEFAULT_POLLING_TIMEOUT),
                pollingAllowedUpdates: config('nutgram.config.polling.allowed_updates',
                    Configuration::DEFAULT_ALLOWED_UPDATES),
                pollingLimit: config('nutgram.config.polling.limit', Configuration::DEFAULT_POLLING_LIMIT),
                enableHttp2: config('nutgram.config.enable_http2', Configuration::DEFAULT_ENABLE_HTTP2),
            );

            if ($app->runningUnitTests()) {
                return Nutgram::fake(config: $configuration);
            }

            $bot = new Nutgram(config('nutgram.token') ?? FakeNutgram::TOKEN, $configuration);

            if ($app->runningInConsole()) {
                $bot->setRunningMode(Polling::class);
            } else {
                $webhook = LaravelWebhook::class;

                if (config('nutgram.safe_mode', false)) {
                    $webhook = new LaravelWebhook(
                        getToken: fn () => request()?->header('X-Telegram-Bot-Api-Secret-Token'),
                        secretToken: md5(config('app.key'))
                    );
                    $webhook->setSafeMode(true);
                }

                $bot->setRunningMode($webhook);
            }

            return $bot;
        });

        $this->app->alias(Nutgram::class, 'nutgram');
        $this->app->alias(Nutgram::class, FakeNutgram::class);
        $this->app->singleton('telegram', fn (Application $app) => $app->get(Nutgram::class));

        if (config('nutgram.mixins', false)) {
            Nutgram::mixin(new Mixins\NutgramMixin());
            File::mixin(new Mixins\FileMixin());
        }
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views/logging', 'logging');

        if ($this->app->runningInConsole()) {
            $this->loadViewsFrom(__DIR__.'/../resources/views/terminal', 'terminal');

            $this->commands([
                Console\RunCommand::class,
                Console\RegisterCommandsCommand::class,
                Console\HookInfoCommand::class,
                Console\HookRemoveCommand::class,
                Console\HookSetCommand::class,
                Console\ListCommand::class,
                Console\MakeCommandCommand::class,
                Console\MakeExceptionCommand::class,
                Console\MakeConversationCommand::class,
                Console\MakeHandlerCommand::class,
                Console\MakeMiddlewareCommand::class,
                Console\IdeGenerateCommand::class,
                Console\LogoutCommand::class,
            ]);

            $this->publishes([
                self::CONFIG_PATH => config_path('nutgram.php'),
                self::ROUTES_PATH => $this->telegramRoutes,
            ], 'nutgram');
        }

        if (config('nutgram.routes', false)) {
            $bot = $this->app->get(Nutgram::class);
            require file_exists($this->telegramRoutes) ? $this->telegramRoutes : self::ROUTES_PATH;
        }
    }
}
