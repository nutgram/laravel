<?php

namespace Nutgram\Laravel\Console;

class MakeExceptionCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:exception {name : Exception name}';

    protected $description = 'Create a new Nutgram API Exception';

    /**
     * @inheritDoc
     */
    protected function getSubDirName(): string
    {
        return 'Exceptions';
    }

    /**
     * @inheritDoc
     */
    protected function getStubPath(): string
    {
        return __DIR__.'/../Stubs/Exception.stub';
    }

    /**
     * @inheritDoc
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['Please provide the Exception name: ', 'E.g. UserBlockedException'],
        ];
    }
}
