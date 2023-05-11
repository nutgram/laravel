<?php

namespace Nutgram\Laravel\Facades;

use DateInterval;
use Illuminate\Support\Facades\Facade;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Handlers\Handler;
use SergiX44\Nutgram\Handlers\HandlerGroup;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Support\BulkMessenger;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Properties\StickerFormat;
use SergiX44\Nutgram\Telegram\Properties\UpdateType;
use SergiX44\Nutgram\Telegram\Types\Chat\Chat;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatAdministratorRights;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatInviteLink;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatJoinRequest;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMember;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatMemberUpdated;
use SergiX44\Nutgram\Telegram\Types\Chat\ChatPermissions;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommandScope;
use SergiX44\Nutgram\Telegram\Types\Command\MenuButton;
use SergiX44\Nutgram\Telegram\Types\Common\Update;
use SergiX44\Nutgram\Telegram\Types\Common\WebhookInfo;
use SergiX44\Nutgram\Telegram\Types\Description\BotDescription;
use SergiX44\Nutgram\Telegram\Types\Description\BotName;
use SergiX44\Nutgram\Telegram\Types\Description\BotShortDescription;
use SergiX44\Nutgram\Telegram\Types\Forum\ForumTopic;
use SergiX44\Nutgram\Telegram\Types\Inline\CallbackQuery;
use SergiX44\Nutgram\Telegram\Types\Inline\ChosenInlineResult;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQuery;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResult;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultsButton;
use SergiX44\Nutgram\Telegram\Types\Input\InputMedia;
use SergiX44\Nutgram\Telegram\Types\Input\InputSticker;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ForceReply;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;
use SergiX44\Nutgram\Telegram\Types\Media\File;
use SergiX44\Nutgram\Telegram\Types\Message\Message;
use SergiX44\Nutgram\Telegram\Types\Message\MessageId;
use SergiX44\Nutgram\Telegram\Types\Payment\PreCheckoutQuery;
use SergiX44\Nutgram\Telegram\Types\Payment\ShippingQuery;
use SergiX44\Nutgram\Telegram\Types\Poll\Poll;
use SergiX44\Nutgram\Telegram\Types\Poll\PollAnswer;
use SergiX44\Nutgram\Telegram\Types\Sticker\MaskPosition;
use SergiX44\Nutgram\Telegram\Types\Sticker\StickerSet;
use SergiX44\Nutgram\Telegram\Types\User\User;
use SergiX44\Nutgram\Telegram\Types\User\UserProfilePhotos;
use SergiX44\Nutgram\Telegram\Types\WebApp\SentWebAppMessage;
use SergiX44\Nutgram\Testing\FakeNutgram;

/**
 * CollectHandlers
 * @method static void middleware($callable)
 * @method static void middlewares($callable)
 * @method static HandlerGroup group(callable $callable)
 * @method static Handler onException($callableOrException, $callable = null)
 * @method static Handler onApiError($callableOrPattern, $callable = null)
 * @method static Handler fallback($callable)
 * @method static Handler fallbackOn(string $type, $callable)
 * @method static void clearErrorHandlers(bool $exception = true, bool $apiError = true)
 * @method static Handler beforeApiRequest($callable)
 * @method static Handler afterApiRequest($callable)
 *
 * UpdateListeners
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
 * @method static Handler onUpdatePoll($callable)
 * @method static Handler onPollAnswer($callable)
 * @method static Handler onMyChatMember($callable)
 * @method static Handler onChatMember($callable)
 * @method static Handler onChatJoinRequest($callable)
 *
 * MessageListeners
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
 * @method static void run()
 * @method static Configuration getConfig()
 * @method static void registerMyCommands()
 * @method static BulkMessenger getBulkMessenger()
 * @method static array currentParameters()
 *
 * Client
 * @method static mixed sendRequest(string $endpoint, array $parameters = [], array $options = [])
 * @method static null|bool downloadFile(File $file, string $path, array $clientOpt = [])
 * @method static null|string downloadUrl(File $file)
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
 * @method static mixed get($key, $default = null)
 * @method static mixed set($key, $value)
 * @method static void delete($key)
 * @method static void clear()
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
 * @method static null|Message sendMessage(string $text, int|string|null $chat_id = null, ?int $message_thread_id = null, ParseMode|string|null $parse_mode = null, ?array $entities = null, ?bool $disable_web_page_preview = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|Message forwardMessage(int|string $chat_id, int|string $from_chat_id, int $message_id, ?int $message_thread_id = null, ?bool $disable_notification = null, ?bool $protect_content = null)
 * @method static null|MessageId copyMessage(int|string $chat_id, int|string $from_chat_id, int $message_id, ?int $message_thread_id = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|Message sendPhoto(InputFile|string $photo, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?bool $has_spoiler = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendAudio(InputFile|string $audio, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?int $duration = null, ?string $performer = null, ?string $title = null, InputFile|string|null $thumbnail = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendDocument(InputFile|string $document, int|string|null $chat_id = null, ?int $message_thread_id = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?bool $disable_content_type_detection = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendVideo(InputFile|string $video, int|string|null $chat_id = null, ?int $message_thread_id = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?bool $has_spoiler = null, ?bool $supports_streaming = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendAnimation(InputFile|string $animation, int|string|null $chat_id = null, ?int $message_thread_id = null, ?int $duration = null, ?int $width = null, ?int $height = null, InputFile|string|null $thumbnail = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?bool $has_spoiler = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendVoice(InputFile|string $voice, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?int $duration = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|Message sendVideoNote(InputFile|string $video_note, int|string|null $chat_id = null, ?int $message_thread_id = null, ?int $duration = null, ?int $length = null, InputFile|string|null $thumbnail = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|array sendMediaGroup(array $media, int|string|null $chat_id = null, ?int $message_thread_id = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, array $clientOpt = [])
 * @method static null|Message sendLocation(float $latitude, float $longitude, int|string|null $chat_id = null, ?int $message_thread_id = null, ?float $horizontal_accuracy = null, ?int $live_period = null, ?int $heading = null, ?int $proximity_alert_radius = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static Message|bool|null editMessageLiveLocation(float $latitude, float $longitude, int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ?float $horizontal_accuracy = null, ?int $heading = null, ?int $proximity_alert_radius = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static Message|bool|null stopMessageLiveLocation(int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static null|Message sendVenue(float $latitude, float $longitude, string $title, string $address, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $foursquare_id = null, ?string $foursquare_type = null, ?string $google_place_id = null, ?string $google_place_type = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|Message sendContact(string $phone_number, string $first_name, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $last_name = null, ?string $vcard = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|Message sendPoll(string $question, array $options, int|string|null $chat_id = null, ?int $message_thread_id = null, ?bool $is_anonymous = null, PollType|string|null $type = null, ?bool $allows_multiple_answers = null, ?int $correct_option_id = null, ?string $explanation = null, ParseMode|string|null $explanation_parse_mode = null, ?array $explanation_entities = null, ?int $open_period = null, ?int $close_date = null, ?bool $is_closed = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|Message sendDice(int|string|null $chat_id = null, ?int $message_thread_id = null, DiceEmoji|string|null $emoji = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null)
 * @method static null|bool sendChatAction(ChatAction|string $action, int|string|null $chat_id = null, ?int $message_thread_id = null)
 * @method static null|UserProfilePhotos getUserProfilePhotos(?int $user_id = null, ?int $offset = null, ?int $limit = null)
 * @method static null|File getFile(string $file_id)
 * @method static null|bool banChatMember(int|string $chat_id, int $user_id, ?int $until_date = null, ?bool $revoke_messages = null)
 * @method static null|bool unbanChatMember(int|string $chat_id, int $user_id, ?bool $only_if_banned = null)
 * @method static null|bool restrictChatMember(int|string $chat_id, int $user_id, ChatPermissions $permissions, ?bool $use_independent_chat_permissions = null, ?int $until_date = null)
 * @method static null|bool promoteChatMember(int|string $chat_id, int $user_id, ?bool $is_anonymous = null, ?bool $can_manage_chat = null, ?bool $can_post_messages = null, ?bool $can_edit_messages = null, ?bool $can_delete_messages = null, ?bool $can_manage_video_chats = null, ?bool $can_restrict_members = null, ?bool $can_promote_members = null, ?bool $can_change_info = null, ?bool $can_invite_users = null, ?bool $can_pin_messages = null, ?bool $can_manage_topics = null)
 * @method static null|bool setChatAdministratorCustomTitle(int|string $chat_id, int $user_id, string $custom_title)
 * @method static null|bool banChatSenderChat(int|string $chat_id, int $sender_chat_id)
 * @method static null|bool unbanChatSenderChat(int|string $chat_id, int $sender_chat_id)
 * @method static null|bool setChatPermissions(int|string $chat_id, ChatPermissions $permissions, ?bool $use_independent_chat_permissions = null)
 * @method static null|string exportChatInviteLink(int|string $chat_id)
 * @method static null|ChatInviteLink createChatInviteLink(int|string $chat_id, ?string $name = null, ?int $expire_date = null, ?int $member_limit = null, ?bool $creates_join_request = null)
 * @method static null|ChatInviteLink editChatInviteLink(int|string $chat_id, string $invite_link, ?string $name = null, ?int $expire_date = null, ?int $member_limit = null, ?bool $creates_join_request = null)
 * @method static null|ChatInviteLink revokeChatInviteLink(int|string $chat_id, string $invite_link)
 * @method static null|bool approveChatJoinRequest(int|string $chat_id, int $user_id)
 * @method static null|bool declineChatJoinRequest(int|string $chat_id, int $user_id)
 * @method static null|bool setChatPhoto(int|string $chat_id, InputFile $photo, array $clientOpt = [])
 * @method static null|bool deleteChatPhoto(int|string $chat_id)
 * @method static null|bool setChatTitle(int|string $chat_id, string $title)
 * @method static null|bool setChatDescription(int|string $chat_id, ?string $description = null)
 * @method static null|bool pinChatMessage(int|string $chat_id, int $message_id, ?bool $disable_notification = null)
 * @method static null|bool unpinChatMessage(int|string $chat_id, ?int $message_id = null)
 * @method static null|bool unpinAllChatMessages(int|string $chat_id)
 * @method static null|bool leaveChat(int|string $chat_id)
 * @method static null|Chat getChat(int|string $chat_id)
 * @method static null|array getChatAdministrators(int|string $chat_id)
 * @method static null|int getChatMemberCount(int|string $chat_id)
 * @method static null|ChatMember getChatMember(int|string $chat_id, int $user_id)
 * @method static null|bool setChatStickerSet(int|string $chat_id, string $sticker_set_name)
 * @method static null|bool deleteChatStickerSet(int|string $chat_id)
 * @method static null|array getForumTopicIconStickers()
 * @method static null|ForumTopic createForumTopic(int|string $chat_id, string $name, ForumIconColor|int|null $icon_color = null, ?string $icon_custom_emoji_id = null)
 * @method static null|bool editForumTopic(int|string $chat_id, int $message_thread_id, ?string $name = null, ?string $icon_custom_emoji_id = null)
 * @method static null|bool closeForumTopic(int|string $chat_id, int $message_thread_id)
 * @method static null|bool reopenForumTopic(int|string $chat_id, int $message_thread_id)
 * @method static null|bool deleteForumTopic(int|string $chat_id, int $message_thread_id)
 * @method static null|bool unpinAllForumTopicMessages(int|string $chat_id, int $message_thread_id)
 * @method static null|bool editGeneralForumTopic(int|string $chat_id, string $name)
 * @method static null|bool closeGeneralForumTopic(int|string $chat_id)
 * @method static null|bool reopenGeneralForumTopic(int|string $chat_id)
 * @method static null|bool hideGeneralForumTopic(int|string $chat_id)
 * @method static null|bool unhideGeneralForumTopic(int|string $chat_id)
 * @method static null|bool answerCallbackQuery(?string $callback_query_id = null, ?string $text = null, ?bool $show_alert = null, ?string $url = null, ?int $cache_time = null)
 * @method static null|bool setMyCommands(array $commands, ?BotCommandScope $scope = null, ?string $language_code = null)
 * @method static null|bool deleteMyCommands(?BotCommandScope $scope = null, ?string $language_code = null)
 * @method static null|array getMyCommands(?BotCommandScope $scope = null, ?string $language_code = null)
 * @method static null|BotName getMyName(?string $language_code = null)
 * @method static null|bool setMyName(?string $name = null, ?string $language_code = null)
 * @method static null|bool setMyDescription(?string $description = null, ?string $language_code = null)
 * @method static null|BotDescription getMyDescription(?string $language_code = null)
 * @method static null|bool setMyShortDescription(?string $short_description = null, ?string $language_code = null)
 * @method static null|BotShortDescription getMyShortDescription(?string $language_code = null)
 * @method static null|bool setChatMenuButton(?int $chat_id = null, ?MenuButton $menu_button = null)
 * @method static null|MenuButton getChatMenuButton(?int $chat_id = null)
 * @method static null|bool setMyDefaultAdministratorRights(?ChatAdministratorRights $rights = null, ?bool $for_channels = null)
 * @method static null|ChatAdministratorRights getMyDefaultAdministratorRights(?bool $for_channels = null)
 *
 * UpdatesMessages
 * @method static Message|bool|null editMessageText(string $text, int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ParseMode|string|null $parse_mode = null, ?array $entities = null, ?bool $disable_web_page_preview = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static Message|bool|null editMessageCaption(int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ?string $caption = null, ParseMode|string|null $parse_mode = null, ?array $caption_entities = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static Message|bool|null editMessageMedia(InputMedia $media, int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ?InlineKeyboardMarkup $reply_markup = null, array $clientOpt = [])
 * @method static Message|bool|null editMessageReplyMarkup(int|string|null $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static null|bool stopPoll(int|string $chat_id, int $message_id, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static null|bool deleteMessage(int|string $chat_id, int $message_id)
 *
 * Stickers
 * @method static null|Message sendSticker(InputFile|string $sticker, int|string|null $chat_id = null, ?int $message_thread_id = null, ?string $emoji = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, InlineKeyboardMarkup|ReplyKeyboardMarkup|ReplyKeyboardRemove|ForceReply|null $reply_markup = null, array $clientOpt = [])
 * @method static null|StickerSet getStickerSet(string $name)
 * @method static null|File uploadStickerFile(InputFile|string $sticker, StickerFormat|string $sticker_format, ?int $user_id = null, array $clientOpt = [])
 * @method static null|bool createNewStickerSet(string $name, string $title, array $stickers, string $sticker_format, ?int $user_id = null, ?string $sticker_type = null, ?bool $needs_repainting = null, array $clientOpt = [])
 * @method static null|bool addStickerToSet(string $name, InputSticker $sticker, ?int $user_id = null, array $clientOpt = [])
 * @method static null|bool setStickerPositionInSet(string $sticker, int $position)
 * @method static null|bool setCustomEmojiStickerSetThumbnail(string $name, ?string $custom_emoji_id = null)
 * @method static null|bool deleteStickerFromSet(string $sticker)
 * @method static null|bool setStickerEmojiList(string $sticker, array $emoji_list)
 * @method static null|bool setStickerKeywords(string $sticker, ?array $keywords = null)
 * @method static null|bool setStickerMaskPosition(string $sticker, ?MaskPosition $mask_position = null)
 * @method static null|bool setStickerSetThumbnail(string $name, ?int $user_id = null, InputFile|string|null $thumbnail = null, array $clientOpt = [])
 * @method static null|array getCustomEmojiStickers(array $custom_emoji_ids)
 * @method static null|bool setStickerSetTitle(string $name, string $title)
 * @method static null|bool deleteStickerSet(string $name)
 *
 * InlineMode
 * @method static null|bool answerInlineQuery(array $results, ?string $inline_query_id = null, ?int $cache_time = null, ?bool $is_personal = null, ?string $next_offset = null, ?InlineQueryResultsButton $button = null)
 * @method static null|SentWebAppMessage answerWebAppQuery(string $web_app_query_id, InlineQueryResult $result)
 *
 * Payments
 * @method static null|Message sendInvoice(string $title, string $description, string $payload, string $provider_token, string $currency, array $prices, int|string|null $chat_id = null, ?int $message_thread_id = null, ?int $max_tip_amount = null, ?array $suggested_tip_amounts = null, ?string $start_parameter = null, ?string $provider_data = null, ?string $photo_url = null, ?int $photo_size = null, ?int $photo_width = null, ?int $photo_height = null, ?bool $need_name = null, ?bool $need_phone_number = null, ?bool $need_email = null, ?bool $need_shipping_address = null, ?bool $send_phone_number_to_provider = null, ?bool $send_email_to_provider = null, ?bool $is_flexible = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static null|string createInvoiceLink(string $title, string $description, string $payload, string $provider_token, string $currency, array $prices, ?int $max_tip_amount = null, ?array $suggested_tip_amounts = null, ?string $provider_data = null, ?string $photo_url = null, ?int $photo_size = null, ?int $photo_width = null, ?int $photo_height = null, ?bool $need_name = null, ?bool $need_phone_number = null, ?bool $need_email = null, ?bool $need_shipping_address = null, ?bool $send_phone_number_to_provider = null, ?bool $send_email_to_provider = null, ?bool $is_flexible = null)
 * @method static null|bool answerShippingQuery(bool $ok, ?string $shipping_query_id = null, ?array $shipping_options = null, ?string $error_message = null)
 * @method static null|bool answerPreCheckoutQuery(bool $ok, ?string $pre_checkout_query_id = null, ?string $error_message = null)
 *
 * Passport
 * @method static null|bool setPassportDataErrors(int $user_id, array $errors)
 *
 * Games
 * @method static null|Message sendGame(string $game_short_name, ?int $chat_id = null, ?int $message_thread_id = null, ?bool $disable_notification = null, ?bool $protect_content = null, ?int $reply_to_message_id = null, ?bool $allow_sending_without_reply = null, ?InlineKeyboardMarkup $reply_markup = null)
 * @method static null|Message|bool setGameScore(int $score, ?int $user_id = null, ?bool $force = null, ?bool $disable_edit_message = null, ?int $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null)
 * @method static null|array getGameHighScores(?int $user_id = null, ?int $chat_id = null, ?int $message_id = null, ?string $inline_message_id = null)
 *
 * UpdateMethods
 * @method static null|array getUpdates(?int $offset = null, ?int $limit = null, ?int $timeout = null, ?array $allowed_updates = null)
 * @method static null|bool setWebhook(string $url, ?InputFile $certificate = null, ?string $ip_address = null, ?int $max_connections = null, ?array $allowed_updates = null, ?bool $drop_pending_updates = null, ?string $secret_token = null)
 * @method static null|bool deleteWebhook(?bool $drop_pending_updates = null)
 * @method static null|WebhookInfo getWebhookInfo()
 *
 * FakeNutgram
 * @method static array getRequestHistory()
 * @method static array getDumpHistory()
 * @method static FakeNutgram willReceive(array $result, bool $ok = true): self
 * @method static FakeNutgram willReceivePartial(array $result, bool $ok = true)
 * @method static FakeNutgram reply()
 * @method static FakeNutgram clearCache()
 * @method static FakeNutgram willStartConversation(bool $remember = true)
 * @method static FakeNutgram dump()
 * @method static FakeNutgram dd()
 * @method static FakeNutgram withoutMiddleware(string|array $middleware)
 * @method static FakeNutgram overrideMiddleware(string|array $middleware)
 *
 * Hears
 * @method static FakeNutgram setCommonUser(User $user)
 * @method static FakeNutgram setCommonChat(Chat $chat)
 * @method static FakeNutgram hearUpdate(Update $update)
 * @method static FakeNutgram hearUpdateType(UpdateType $type, array $partialAttributes = [], bool $fillNullableFields = false)
 * @method static FakeNutgram hearMessage(array $value)
 * @method static FakeNutgram hearText(string $value)
 * @method static FakeNutgram hearCallbackQueryData(string $value)
 *
 * Asserts
 * @method static FakeNutgram assertRaw(callable $closure, int $index = 0)
 * @method static FakeNutgram assertCalled(string $method, int $times = 1)
 * @method static FakeNutgram assertReply(string|array $method, ?array $expected = null, int $index = 0)
 * @method static FakeNutgram assertReplyMessage(array $expected, int $index = 0, ?string $forceMethod = null)
 * @method static FakeNutgram assertReplyText(string $expected, int $index = 0)
 * @method static FakeNutgram assertActiveConversation(?int $userId = null, ?int $chatId = null)
 * @method static FakeNutgram assertNoConversation(?int $userId = null, ?int $chatId = null)
 * @method static FakeNutgram assertNoReply()
 */
class Telegram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'telegram';
    }

    public static function fake(): void
    {
        static::swap(Nutgram::fake());
    }
}
