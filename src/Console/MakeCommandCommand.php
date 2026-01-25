<?php

namespace Nutgram\Laravel\Console;

class MakeCommandCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:command {name : Command name}';

    protected $description = 'Create a new Nutgram Command';

    /**
     * @inheritDoc
     */
    protected function getSubDirName(): string
    {
        return 'Commands';
    }

    /**
     * @inheritDoc
     */
    protected function getStubPath(): string
    {
        return __DIR__.'/../Stubs/Command.stub';
    }

    /**
     * @inheritDoc
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['Please provide the Command name:', 'E.g. StartCommand'],
        ];
    }
}
