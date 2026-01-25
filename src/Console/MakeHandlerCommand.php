<?php

namespace Nutgram\Laravel\Console;

class MakeHandlerCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:handler {name : Handler name}';

    protected $description = 'Create a new Nutgram Handler';

    /**
     * @inheritDoc
     */
    protected function getSubDirName(): string
    {
        return 'Handlers';
    }

    /**
     * @inheritDoc
     */
    protected function getStubPath(): string
    {
        return __DIR__.'/../Stubs/Handler.stub';
    }

    /**
     * @inheritDoc
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['Please provide the Handler name:', 'E.g. PhotoHandler'],
        ];
    }
}
