<?php

namespace Nutgram\Laravel\Console;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use SergiX44\Nutgram\Handlers\Handler;
use SergiX44\Nutgram\Handlers\Type\InternalCommand;
use SergiX44\Nutgram\Nutgram;
use function Termwind\render;

class ListCommand extends Command
{
    protected $signature = 'nutgram:list {--json}';

    protected $description = 'List all registered handlers';

    public function handle(Nutgram $bot): int
    {
        $handlers = $this->getHandlers($bot);

        if ($handlers->isEmpty()) {
            $this->outputComponents()->error("Your application doesn't have any handlers.");

            return self::FAILURE;
        }

        $this->option('json') ? $this->asJson($handlers) : $this->forCli($handlers);

        return self::SUCCESS;
    }

    protected function asJson(Collection $handlers): void
    {
        $this->output->writeln($handlers->toJson());
    }

    protected function forCli(Collection $handlers): void
    {
        $handlers = $handlers->map(function ($item) {
            $item['pattern'] = preg_replace_callback('/\{([^}]+)\}/', function ($matches) {
                return sprintf('<fg=yellow>{%s}</>', $matches[1]);
            }, $item['pattern']);

            $item['callable'] = $this->renameCallableForCli($item['callable']);

            return $item;
        });

        render(view('terminal::list', [
            'items' => $handlers,
            'handlerWidth' => $handlers->max(fn ($item) => strlen($item['handler'])),
        ]));
    }

    protected function getHandlers(Nutgram $bot): Collection
    {
        $handlers = (fn () => $this->handlers)->call($bot);

        return collect(Arr::dot($handlers))
            ->map(fn (Handler $handler, string $key) => [
                'handler' => $this->getHandlerName($key, $handler instanceof InternalCommand),
                'pattern' => $handler->getPattern(),
                'callable' => $this->getCallableName($handler),
            ])
            ->values();
    }

    protected function getHandlerName(string $signature, bool $isCommand = false): string
    {
        if ($isCommand) {
            return 'onCommand';
        }

        $signature = Str::lower($signature);

        return match (Str::before($signature, '.')) {
            'message' => $this->getMessageHandlerName($signature),
            'edited_message' => 'onEditedMessage',
            'channel_post' => 'onChannelPost',
            'edited_channel_post' => 'onEditedChannelPost',
            'business_connection' => 'onBusinessConnection',
            'business_message' => 'onBusinessMessage',
            'edited_business_message' => 'onEditedBusinessMessage',
            'deleted_business_message' => 'onDeletedBusinessMessages',
            'message_reaction' => 'onMessageReaction',
            'message_reaction_count' => 'onMessageReactionCount',
            'inline_query' => 'onInlineQuery',
            'chosen_inline_result' => 'onChosenInlineResult',
            'callback_query' => 'onCallbackQuery',
            'shipping_query' => 'onShippingQuery',
            'pre_checkout_query' => 'onPreCheckoutQuery',
            'purchased_paid_media' => 'onPaidMediaPurchased',
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

    protected function getMessageHandlerName(string $signature): string
    {
        $subHandlerSignature = Str::after($signature, 'message.');
        $subHandlerName = explode('.', $subHandlerSignature)[0] ?? null;

        if (is_numeric($subHandlerName)) {
            return 'onMessage';
        }

        return match ($subHandlerName) {
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
            'refunded_payment' => 'onRefundedPayment',
            'users_shared' => 'onUsersShared',
            'chat_shared' => 'onChatShared',
            'connected_website' => 'onConnectedWebsite',
            'passport_data' => 'onPassportData',
            'proximity_alert_triggered' => 'onProximityAlertTriggered',
            'boost_added' => 'onBoostAdded',
            'direct_message_price_changed' => 'onDirectMessagePriceChanged',
            'checklist' => 'onChecklist',
            'checklist_tasks_done' => 'onChecklistTasksDone',
            'checklist_tasks_added' => 'onChecklistTasksAdded',
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
                return sprintf("%s@%s", get_class($callable[0]), trim($callable[1]));
            }

            return sprintf("%s@%s", trim($callable[0]), trim($callable[1]));
        }

        if ($callable instanceof Closure) {
            return 'closure';
        }

        return 'unknown';
    }

    protected function renameCallableForCli(string $callable): string
    {
        $path = str(config('nutgram.namespace'))
            ->replace(base_path(), '')
            ->substr(1)
            ->ucfirst()
            ->replace(DIRECTORY_SEPARATOR, '\\')
            ->append('\\')
            ->toString();

        return str_replace($path, '', $callable);
    }
}
