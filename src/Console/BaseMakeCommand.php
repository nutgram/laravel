<?php

namespace Nutgram\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

abstract class BaseMakeCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'nutgram:make:? {name : ? name}';

    protected $description = 'Create a new Nutgram ?';

    public function handle(): int
    {
        $path = sprintf("%s%s%s%s%s.php",
            config('nutgram.namespace'),
            DIRECTORY_SEPARATOR,
            $this->getSubDirName(),
            DIRECTORY_SEPARATOR,
            $this->argument('name'),
        );

        $this->makeDirectory($path);

        if (File::exists($path)) {
            $this->outputComponents()->error(sprintf("%s already exists.", Str::after($path, base_path())));

            return self::FAILURE;
        }

        File::put($path, $this->getStubContent($this->getStubPath(), $this->getStubVariables()));

        $this->outputComponents()->success('Nutgram '.Str::singular($this->getSubDirName()).' created successfully.');

        return self::SUCCESS;
    }

    /**
     * Return the subdirectory name
     * @return string
     */
    abstract protected function getSubDirName(): string;

    /**
     * Return the stub file path
     * @return string
     */
    abstract protected function getStubPath(): string;

    /**
     * Map the stub variables present in stub to its value
     * @return array<string, string>
     */
    protected function getStubVariables(): array
    {
        /** @var string $name */
        $name = $this->argument('name');

        return [
            'namespace' => $this->getSubDirName().$this->getNamespace($name),
            'name' => class_basename($name),
        ];
    }

    /**
     * Replace the stub variables with the desire value
     * @param  string  $path
     * @param  array<string, string>  $variables
     * @return string
     */
    protected function getStubContent(string $path, array $variables = []): string
    {
        $content = File::get($path);
        foreach ($variables as $key => $value) {
            $content = str_replace("{{ $key }}", $value, $content);
        }
        return $content;
    }

    /**
     * Build the directory for the class if necessary
     * @param  string  $path
     * @return void
     */
    protected function makeDirectory(string $path): void
    {
        $path = dirname($path);

        if (File::isDirectory($path)) {
            return;
        }

        if (!File::makeDirectory($path, 0755, true)) {
            throw new RuntimeException('Unable to create directory: '.$path);
        }
    }

    /**
     * Get the full namespace for a given class, without the class name
     * @param  string  $name
     * @return string
     */
    protected function getNamespace(string $name): string
    {
        $namespace = $this->removeClassName($this->slashesTrim($name));

        if (empty($namespace)) {
            return '';
        }

        return '\\'.$namespace;
    }


    /**
     * Remove duplicated slashes
     * @param  string  $name
     * @param  string  $replacement
     * @return string
     */
    protected function slashesTrim(string $name, string $replacement = '\\'): string
    {
        return preg_replace('#[\\\/]+#', $replacement, $name);
    }

    /**
     * Returns namespace from the full class name
     * @param  string  $name
     * @return string
     */
    protected function removeClassName(string $name): string
    {
        return trim(dirname($name), '\\/.');
    }
}
