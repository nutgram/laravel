<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class IdeGenerateCommand extends Command
{
    protected $signature = 'nutgram:ide:generate';

    protected $description = 'Generate IDE helper for Nutgram';

    public function handle(): int
    {
        File::put(
            path: base_path('_ide_helper_nutgram.php'),
            contents: file_get_contents(__DIR__.'/../Stubs/Ide.stub'),
        );

        $this->outputComponents()->success('IDE helper generated successfully.');

        return self::SUCCESS;
    }
}
