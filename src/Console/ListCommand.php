<?php

namespace Nutgram\Laravel\Console;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SergiX44\Nutgram\Handlers\Handler;
use SergiX44\Nutgram\Handlers\Type\Command as NutgramCommand;
use SergiX44\Nutgram\Nutgram;
use function Termwind\render;

class ListCommand extends Command
{
    protected $signature = 'nutgram:list';

    protected $description = 'List all registered handlers';

    public function handle(Nutgram $bot): int
    {
        $handlers = $this->getHandlers($bot)->map(fn (Handler $handler, string $key) => [
            'handler' => $this->getHandlerName($key, $handler instanceof NutgramCommand),
            'pattern' => $handler->getPattern(),
            'callable' => $this->getCallableName($handler),
        ]);

        if ($handlers->isEmpty()) {
            $this->warn("No handlers have been registered.");
            return 0;
        }

        render(view('terminal::list', ['items' => $handlers]));

        return 0;
    }

    protected function getHandlers(Nutgram $bot): Collection
    {
        $handlers = (fn () => $this->handlers)->call($bot);
        return collect(Arr::dot($handlers));
    }

    protected function getHandlerName(string $signature, bool $isCommand = false): string
    {
        if ($isCommand) {
            return 'onCommand';
        }

        return $this->resolveUpdateListener(Str::lower($signature));
    }

    protected function resolveUpdateListener(string $signature): string
    {
        return match (Str::before($signature, '.')) {
            'message' => $this->resolveMessageListener($signature),
            'edited_message' => 'onEditedMessage',
            'channel_post' => 'onChannelPost',
            'edited_channel_post' => 'onEditedChannelPost',
            'message_reaction' => 'onMessageReaction',
            'message_reaction_count' => 'onMessageReactionCount',
            'inline_query' => 'onInlineQuery',
            'chosen_inline_result' => 'onChosenInlineResult',
            'callback_query' => 'onCallbackQuery',
            'shipping_query' => 'onShippingQuery',
            'pre_checkout_query' => 'onPreCheckoutQuery',
            'poll' => 'onUpdatePoll',
            'poll_answer' => 'onPollAnswer',
            'my_chat_member' => 'onMyChatMember',
            'chat_member' => 'onChatMember',
            'chat_join_request' => 'onChatJoinRequest',
            'chat_boost' => 'onChatBoost',
            'removed_chat_boost' => 'onRemovedChatBoost',
            'api_error' => 'onApiError',
            'exception' => 'onException',
            'fallback' => 'fallback',
            'before_api_request' => 'beforeApiRequest',
            'after_api_request' => 'afterApiRequest',
            default => 'unknown',
        };
    }

    protected function resolveMessageListener(string $signature): string
    {
        $subHandlerSignature = Str::after($signature, 'message.');
        $subHandlerName = explode('.', $subHandlerSignature)[0] ?? null;

        if(is_numeric($subHandlerName)) {
            return 'onMessage';
        }

        return match($subHandlerName){
            'text' => 'onText',
            'animation' => 'onAnimation',
            'audio' => 'onAudio',
            'document' => 'onDocument',
            'photo' => 'onPhoto',
            'sticker' => 'onSticker',
            'video' => 'onVideo',
            'video_note' => 'onVideoNote',
            'voice' => 'onVoice',
            'contact' => 'onContact',
            'dice' => 'onDice',
            'game' => 'onGame',
            'poll' => 'onMessagePoll',
            'venue' => 'onVenue',
            'location' => 'onLocation',
            'story' => 'onStory',
            'new_chat_members' => 'onNewChatMembers',
            'left_chat_member' => 'onLeftChatMember',
            'new_chat_title' => 'onNewChatTitle',
            'new_chat_photo' => 'onNewChatPhoto',
            'delete_chat_photo' => 'onDeleteChatPhoto',
            'group_chat_created' => 'onGroupChatCreated',
            'supergroup_chat_created' => 'onSupergroupChatCreated',
            'channel_chat_created' => 'onChannelChatCreated',
            'message_auto_delete_timer_changed' => 'onMessageAutoDeleteTimerChanged',
            'migrate_to_chat_id' => 'onMigrateToChatId',
            'migrate_from_chat_id' => 'onMigrateFromChatId',
            'pinned_message' => 'onPinnedMessage',
            'invoice' => 'onInvoice',
            'successful_payment' => 'onSuccessfulPayment',
            'users_shared' => 'onUsersShared',
            'chat_shared' => 'onChatShared',
            'connected_website' => 'onConnectedWebsite',
            'passport_data' => 'onPassportData',
            'proximity_alert_triggered' => 'onProximityAlertTriggered',
            'forum_topic_created' => 'onForumTopicCreated',
            'forum_topic_edited' => 'onForumTopicEdited',
            'forum_topic_closed' => 'onForumTopicClosed',
            'forum_topic_reopened' => 'onForumTopicReopened',
            'giveaway_created' => 'onGiveawayCreated',
            'giveaway' => 'onGiveaway',
            'giveaway_winners' => 'onGiveawayWinners',
            'giveaway_completed' => 'onGiveawayCompleted',
            'video_chat_scheduled' => 'onVideoChatScheduled',
            'video_chat_started' => 'onVideoChatStarted',
            'video_chat_ended' => 'onVideoChatEnded',
            'video_chat_participants_invited' => 'onVideoChatParticipantsInvited',
            'web_app_data' => 'onWebAppData',
            default => 'unknown',
        };
    }

    protected function getCallableName(Handler $handler): string
    {
        // get callable value
        $callable = (fn () => $this->callable)->call($handler);

        // parse callable
        if (is_string($callable)) {
            return trim($callable);
        }

        if (is_array($callable)) {
            if (is_object($callable[0])) {
                return sprintf("%s::%s", get_class($callable[0]), trim($callable[1]));
            }

            return sprintf("%s::%s", trim($callable[0]), trim($callable[1]));
        }

        if ($callable instanceof Closure) {
            return 'closure';
        }

        return 'unknown';
    }
}
