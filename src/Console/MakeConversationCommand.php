<?php

namespace Nutgram\Laravel\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;

class MakeConversationCommand extends BaseMakeCommand
{
    protected $signature = 'nutgram:make:conversation {name : Conversation name} {--menu : Create an inline menu}';

    protected $description = 'Create a new Nutgram Conversation';

    /**
     * @inheritDoc
     */
    protected function getSubDirName(): string
    {
        return 'Conversations';
    }

    /**
     * @inheritDoc
     */
    protected function getStubPath(): string
    {
        if ($this->option('menu')) {
            return __DIR__.'/../Stubs/InlineMenu.stub';
        }

        return __DIR__.'/../Stubs/Conversation.stub';
    }

    /**
     * @inheritDoc
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => ['Please provide the Conversation name:', 'E.g. FeedbackConversation'],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $input->setOption('menu', confirm(
            label: 'Would you like to make an inline menu conversation?',
            default: $this->option('menu')
        ));
    }
}
