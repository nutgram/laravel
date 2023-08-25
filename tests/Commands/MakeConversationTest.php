<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\Console\MakeConversationCommand;

beforeEach(function () {
    File::deleteDirectory(config('nutgram.namespace'));
});

test('nutgram:make:conversation makes a conversation', function () {
    $this->artisan(MakeConversationCommand::class, ['name' => 'MyConversation'])
        ->expectsOutput('Nutgram Conversation created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Conversations/MyConversation.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyConversation extends Conversation');
});

test('nutgram:make:conversation makes a conversation menu', function () {
    $this->artisan(MakeConversationCommand::class, ['name' => 'MyConversationMenu', '--menu' => true])
        ->expectsOutput('Nutgram Conversation created successfully.')
        ->assertSuccessful();

    expect(config('nutgram.namespace').'/Conversations/MyConversationMenu.php')
        ->toBeFile()
        ->getFileContent()
        ->toContain('class MyConversationMenu extends InlineMenu');
});
