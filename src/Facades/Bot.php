<?php

namespace Nutgram\Laravel\Facades;

use DateInterval;
use Illuminate\Support\Facades\Facade;
use SergiX44\Nutgram\Handlers\Handler;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Support\BulkMessenger;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatAdministratorRights;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatInviteLink;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatJoinRequest;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMember;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMemberUpdated;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatPermissions;
use SergiX44\Nutgram\Telegram\Types\Command\MenuButton;
use SergiX44\Nutgram\Telegram\Types\Common\Update;
use SergiX44\Nutgram\Telegram\Types\Forum\ForumTopic;
use SergiX44\Nutgram\Telegram\Types\Inline\CallbackQuery;
use SergiX44\Nutgram\Telegram\Types\Inline\ChosenInlineResult;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQuery;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResult;
use SergiX44\Nutgram\Telegram\Types\Media\File;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use SergiX44\Nutgram\Telegram\Types\Message\MessageId;
use SergiX44\Nutgram\Telegram\Types\Payment\PreCheckoutQuery;
use SergiX44\Nutgram\Telegram\Types\Payment\ShippingQuery;
use SergiX44\Nutgram\Telegram\Types\Poll\Poll;
use SergiX44\Nutgram\Telegram\Types\Poll\PollAnswer;
use SergiX44\Nutgram\Telegram\Types\Sticker\StickerSet;
use SergiX44\Nutgram\Telegram\Types\User\User;
use SergiX44\Nutgram\Telegram\Types\User\UserProfilePhotos;
use SergiX44\Nutgram\Telegram\Types\WebApp\SentWebAppMessage;

/**
 * CollectHandlers
 * @method static void middleware($callable)
 * @method static Handler onException($callableOrException, $callable = null)
 * @method static Handler beforeApiRequest($callable)
 * @method static Handler afterApiRequest($callable)
 * @method static Handler onApiError($callableOrPattern, $callable = null)
 * @method static Handler fallback($callable)
 * @method static Handler fallbackOn(string $type, $callable)
 * @method static void clearErrorHandlers(bool $exception = true, bool $apiError = true)
 *
 * UpdateHandlers
 * @method static Handler onMessage($callable)
 * @method static Handler onMessageType(string $type, $callable)
 * @method static Handler onEditedMessage($callable)
 * @method static Handler onChannelPost($callable)
 * @method static Handler onEditedChannelPost($callable)
 * @method static Handler onInlineQuery($callable)
 * @method static Handler onChosenInlineResult($callable)
 * @method static Handler onCallbackQuery($callable)
 * @method static Handler onCallbackQueryData(string $pattern, $callable)
 * @method static Handler onShippingQuery($callable)
 * @method static Handler onPreCheckoutQuery($callable)
 * @method static Handler onPreCheckoutQueryPayload(string $pattern, $callable)
 * @method static Handler onPoll($callable)
 * @method static Handler onUpdatePoll($callable)
 * @method static Handler onPollAnswer($callable)
 * @method static Handler onMyChatMember($callable)
 * @method static Handler onChatMember($callable)
 * @method static Handler onChatJoinRequest($callable)
 *
 * MessageHandlers
 * @method static Command onCommand(string $command, $callable)
 * @method static Command registerCommand(string|Command $command)
 * @method static Handler onText(string $pattern, $callable)
 * @method static Handler onAnimation($callable)
 * @method static Handler onAudio($callable)
 * @method static Handler onDocument($callable)
 * @method static Handler onPhoto($callable)
 * @method static Handler onSticker($callable)
 * @method static Handler onVideo($callable)
 * @method static Handler onVideoNote($callable)
 * @method static Handler onVoice($callable)
 * @method static Handler onContact($callable)
 * @method static Handler onDice($callable)
 * @method static Handler onGame($callable)
 * @method static Handler onMessagePoll($callable)
 * @method static Handler onVenue($callable)
 * @method static Handler onLocation($callable)
 * @method static Handler onNewChatMembers($callable)
 * @method static Handler onLeftChatMember($callable)
 * @method static Handler onNewChatTitle($callable)
 * @method static Handler onNewChatPhoto($callable)
 * @method static Handler onDeleteChatPhoto($callable)
 * @method static Handler onGroupChatCreated($callable)
 * @method static Handler onSupergroupChatCreated($callable)
 * @method static Handler onChannelChatCreated($callable)
 * @method static Handler onMessageAutoDeleteTimerChanged($callable)
 * @method static Handler onMigrateToChatId($callable)
 * @method static Handler onMigrateFromChatId($callable)
 * @method static Handler onPinnedMessage($callable)
 * @method static Handler onInvoice($callable)
 * @method static Handler onSuccessfulPayment($callable)
 * @method static Handler onSuccessfulPaymentPayload(string $pattern, $callable)
 * @method static Handler onConnectedWebsite($callable)
 * @method static Handler onPassportData($callable)
 * @method static Handler onProximityAlertTriggered($callable)
 * @method static Handler onForumTopicCreated($callable)
 * @method static Handler onForumTopicClosed($callable)
 * @method static Handler onForumTopicReopened($callable)
 * @method static Handler onVideoChatScheduled($callable)
 * @method static Handler onVideoChatStarted($callable)
 * @method static Handler onVideoChatEnded($callable)
 * @method static Handler onVideoChatParticipantsInvited($callable)
 * @method static Handler onWebAppData($callable)
 *
 * Nutgram
 * @method static BulkMessenger getBulkMessenger()
 *
 * UserCacheProxy
 * @method static mixed getUserData($key, ?int $userId = null, $default = null)
 * @method static bool setUserData($key, $value, ?int $userId = null, DateInterval|int|null $ttl = null)
 * @method static bool deleteUserData($key, ?int $userId = null)
 *
 * GlobalCacheProxy
 * @method static mixed getGlobalData($key, $default = null)
 * @method static bool setGlobalData($key, $value, DateInterval|int|null $ttl = null)
 * @method static bool deleteGlobalData($key)
 *
 * UpdateDataProxy
 * @method static mixed getData($key, $default = null)
 * @method static mixed setData($key, $value)
 * @method static void deleteData($key)
 * @method static void clearData()
 *
 * UpdateProxy
 * @method static null|Update update()
 * @method static null|int chatId()
 * @method static null|Chat chat()
 * @method static null|int userId()
 * @method static null|User user()
 * @method static null|int messageId()
 * @method static null|Message message()
 * @method static bool isCallbackQuery()
 * @method static null|CallbackQuery callbackQuery()
 * @method static bool isInlineQuery()
 * @method static null|InlineQuery inlineQuery()
 * @method static null|ChosenInlineResult chosenInlineResult()
 * @method static null|ShippingQuery shippingQuery()
 * @method static bool isPreCheckoutQuery()
 * @method static null|PreCheckoutQuery preCheckoutQuery()
 * @method static null|Poll poll()
 * @method static null|PollAnswer pollAnswer()
 * @method static bool isMyChatMember()
 * @method static null|ChatMemberUpdated chatMember()
 * @method static null|ChatJoinRequest chatJoinRequest()
 * @method static null|string inlineMessageId()
 * @method static bool isCommand()
 *
 * AvailableMethods
 * @method static null|User getMe()
 * @method static null|bool logOut()
 * @method static null|bool close()
 * @method static null|Message|bool sendMessage(string $text, array $opt = [])
 * @method static null|Message forwardMessage(string|int $chat_id, string|int $from_chat_id, int $message_id, array $opt = [])
 * @method static null|MessageId copyMessage(string|int $chat_id, string|int $from_chat_id, int $message_id, array $opt = [])
 * @method static null|Message sendPhoto(mixed $photo, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendAudio(mixed $audio, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendDocument(mixed $document, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendVideo(mixed $video, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendAnimation(mixed $animation, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendVoice(mixed $voice, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendVideoNote(mixed $video_note, array $opt = [], array $clientOpt = [])
 * @method static null|array sendMediaGroup(array $media, array $opt = [], array $clientOpt = [])
 * @method static null|Message sendLocation(float $latitude, float $longitude, array $opt = [])
 * @method static null|Message|bool editMessageLiveLocation(float $latitude, float $longitude, array $opt = [])
 * @method static null|Message|bool stopMessageLiveLocation(array $opt = [])
 * @method static null|Message sendVenue(float $latitude, float $longitude, string $title, string $address, array $opt = [])
 * @method static null|Message sendContact(string $first_name, string $phone_number, array $opt = [])
 * @method static null|Message sendPoll(string $question, array $options, array $opt = [])
 * @method static null|Message sendDice(array $opt = [])
 * @method static null|Message sendChatAction(string $action, array $opt = [])
 * @method static null|UserProfilePhotos getUserProfilePhotos(array $opt = [])
 * @method static null|File getFile(string $file_id)
 * @method static null|bool banChatMember(string|int $chat_id, int $user_id, array $opt = [])
 * @method static null|bool unbanChatMember(string|int $chat_id, int $user_id, array $opt = [])
 * @method static null|bool restrictChatMember(string|int $chat_id, int $user_id, ChatPermissions $permissions, array $opt = [])
 * @method static null|bool promoteChatMember(string|int $chat_id, int $user_id, array $opt = [])
 * @method static null|bool setChatAdministratorCustomTitle(string|int $chat_id, int $user_id, string $custom_title, array $opt = [])
 * @method static null|bool banChatSenderChat(string|int $chat_id, int $sender_chat_id, array $opt = [])
 * @method static null|bool unbanChatSenderChat(string|int $chat_id, int $sender_chat_id)
 * @method static null|bool setChatPermissions(string|int $chat_id, ChatPermissions $permissions, array $opt = [])
 * @method static null|string exportChatInviteLink(string|int $chat_id)
 * @method static null|ChatInviteLink createChatInviteLink(string|int $chat_id, array $opt = [])
 * @method static null|ChatInviteLink editChatInviteLink(string|int $chat_id, string $invite_link, array $opt = [])
 * @method static null|ChatInviteLink revokeChatInviteLink(string|int $chat_id, string $invite_link)
 * @method static null|bool approveChatJoinRequest(string|int $chat_id, int $user_id)
 * @method static null|bool declineChatJoinRequest(string|int $chat_id, int $user_id)
 * @method static null|bool setChatPhoto(string|int $chat_id, mixed $photo, array $clientOpt = [])
 * @method static null|bool deleteChatPhoto(string|int $chat_id)
 * @method static null|bool setChatTitle(string|int $chat_id, string $title)
 * @method static null|bool setChatDescription(string|int $chat_id, ?string $description = null)
 * @method static null|bool pinChatMessage(string|int $chat_id, int $message_id, array $opt = [])
 * @method static null|bool unpinChatMessage(string|int $chat_id, ?int $message_id = null)
 * @method static null|bool unpinAllChatMessages(string|int $chat_id)
 * @method static null|bool leaveChat(string|int $chat_id)
 * @method static null|Chat getChat(string|int $chat_id)
 * @method static null|array getChatAdministrators(string|int $chat_id)
 * @method static null|int getChatMemberCount(string|int $chat_id)
 * @method static null|ChatMember getChatMember(string|int $chat_id, int $user_id)
 * @method static null|bool setChatStickerSet(string|int $chat_id, string $sticker_set_name)
 * @method static null|bool deleteChatStickerSet(string|int $chat_id)
 * @method static null|array getForumTopicIconStickers()
 * @method static null|ForumTopic createForumTopic(string|int $chat_id, string $name, array $opt = [])
 * @method static null|bool editForumTopic(string|int $chat_id, int $message_thread_id, array $opt = [])
 * @method static null|bool closeForumTopic(string|int $chat_id, int $message_thread_id)
 * @method static null|bool reopenForumTopic(string|int $chat_id, int $message_thread_id)
 * @method static null|bool deleteForumTopic(string|int $chat_id, int $message_thread_id)
 * @method static null|bool unpinAllForumTopicMessages(string|int $chat_id, int $message_thread_id)
 * @method static null|bool editGeneralForumTopic(string|int $chat_id, string $name)
 * @method static null|bool closeGeneralForumTopic(string|int $chat_id)
 * @method static null|bool reopenGeneralForumTopic(string|int $chat_id)
 * @method static null|bool hideGeneralForumTopic(string|int $chat_id)
 * @method static null|bool unhideGeneralForumTopic(string|int $chat_id)
 * @method static null|bool answerCallbackQuery(array $opt = [])
 * @method static null|bool setMyCommands(array $commands = [], array $opt = [])
 * @method static bool deleteMyCommands(array $opt = [])
 * @method static null|array getMyCommands(array $opt = [])
 * @method static null|bool setChatMenuButton(array $opt = [])
 * @method static null|MenuButton getChatMenuButton(array $opt = [])
 * @method static null|bool setMyDefaultAdministratorRights(array $opt = [])
 * @method static null|ChatAdministratorRights getMyDefaultAdministratorRights(array $opt = [])
 *
 * UpdatesMessages
 * @method static null|Message|bool editMessageText(string $text, array $opt = [])
 * @method static null|Message|bool editMessageCaption(array $opt = [])
 * @method static null|Message|bool editMessageMedia(array $mediaArray, array $opt = [], array $clientOpt = [])
 * @method static null|Message|bool editMessageReplyMarkup(array $opt = [])
 * @method static null|bool stopPoll(string|int $chat_id, int $message_id, array $opt = [])
 * @method static null|bool deleteMessage(string|int $chat_id, int $message_id)
 *
 * Stickers
 * @method static null|Message sendSticker(mixed $sticker, array $opt = [], array $clientOpt = [])
 * @method static null|StickerSet getStickerSet(string $name)
 * @method static null|File uploadStickerFile(mixed $png_sticker, array $opt = [], array $clientOpt = [])
 * @method static null|bool createNewStickerSet(string $name, string $title, array $opt = [], array $clientOpt = [])
 * @method static null|bool addStickerToSet(string $name, array $opt = [], array $clientOpt = [])
 * @method static null|bool setStickerPositionInSet(string $sticker, int $position)
 * @method static null|bool deleteStickerFromSet(string $sticker)
 * @method static null|bool setStickerSetThumb(string $name, array $opt = [], array $clientOpt = [])
 * @method static null|array getCustomEmojiStickers(array $emoji_ids)
 *
 * InlineMode
 * @method static null|bool answerInlineQuery(array $results, array $opt = [])
 * @method static null|SentWebAppMessage answerWebAppQuery(string $web_app_query_id, InlineQueryResult $result)
 *
 * Payments
 * @method static null|Message sendInvoice(string $title, string $description, string $payload, string $provider_token, string $currency, array $prices, array $opt = [])
 * @method static string createInvoiceLink(string $title, string $description, string $payload, string $provider_token, string $currency, array $prices, array $opt = [])
 * @method static null|bool answerShippingQuery(bool $ok, array $opt = [])
 * @method static null|bool answerPreCheckoutQuery(bool $ok, array $opt = [])
 *
 * Passport
 * @method static null|bool setPassportDataErrors(int $user_id, array $errors)
 *
 * Games
 * @method static null|Message sendGame(string $game_short_name, array $opt = [])
 * @method static null|bool setGameScore(int $score, array $opt = [])
 * @method static null|array getGameHighScores(array $opt = [])
 */
class Bot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bot';
    }

    public static function fake(): Nutgram
    {
        return Nutgram::fake();
    }
}
