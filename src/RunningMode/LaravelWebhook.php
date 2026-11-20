<?php

namespace Nutgram\Laravel\RunningMode;

use LogicException;
use SergiX44\Nutgram\RunningMode\Webhook;

class LaravelWebhook extends Webhook
{
    public function canRespond(): bool
    {
        $webhookResponse = $this->webhookResponse();

        return $webhookResponse !== null && $webhookResponse->call === null;
    }

    public function respond(string $method, array $parameters): void
    {
        $webhookResponse = $this->webhookResponse()
            ?? throw new LogicException('The SendWebhookResponse middleware is not registered on the webhook route.');

        $webhookResponse->call = ['method' => $method, ...$parameters];
    }

    protected function input(): ?string
    {
        return request()?->getContent();
    }

    private function webhookResponse(): ?WebhookResponse
    {
        $webhookResponse = request()->attributes->get(WebhookResponse::class);

        return $webhookResponse instanceof WebhookResponse ? $webhookResponse : null;
    }
}
