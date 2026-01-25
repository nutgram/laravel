<?php

namespace Nutgram\Laravel\Console;

class MakeMiddlewareCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:middleware {name : Middleware name}';

    protected $description = 'Create a new Nutgram Middleware';

    /**
     * @inheritDoc
     */
    protected function getSubDirName(): string
    {
        return 'Middleware';
    }

    /**
     * @inheritDoc
     */
    protected function getStubPath(): string
    {
        return __DIR__.'/../Stubs/Middleware.stub';
    }

    /**
     * @inheritDoc
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['Please provide the Middleware name:', 'E.g. CheckMaintenance'],
        ];
    }
}
