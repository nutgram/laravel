<?php

namespace Nutgram\Laravel\Console;

class MakeExceptionCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:exception {name : Exception name}';

    protected $description = 'Create a new Nutgram API Exception';

    /**
     * Return the sub directory name
     * @return string
     */
    protected function getSubDirName(): string
    {
        return 'Exceptions';
    }

    /**
     * Return the stub file path
     * @return string
     */
    protected function getStubPath(): string
    {
        return __DIR__.'/../Stubs/Exception.stub';
    }
}
