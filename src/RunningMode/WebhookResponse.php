<?php

namespace Nutgram\Laravel\RunningMode;

/**
 * Holds the Bot API call that answers the current webhook request.
 * SendWebhookResponse puts it in the request attributes, LaravelWebhook fills it.
 */
final class WebhookResponse
{
    /**
     * @var array<array-key, mixed>|null
     */
    public ?array $call = null;
}
