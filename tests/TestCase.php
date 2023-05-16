<?php

namespace Nutgram\Laravel\Tests;

use Illuminate\Foundation\Application;
use Nutgram\Laravel\NutgramServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Termwind\Laravel\TermwindServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            NutgramServiceProvider::class,
            TermwindServiceProvider::class,
        ];
    }
}
