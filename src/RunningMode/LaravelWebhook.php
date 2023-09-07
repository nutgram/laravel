<?php

namespace Nutgram\Laravel\RunningMode;

use SergiX44\Nutgram\RunningMode\Webhook;

class LaravelWebhook extends Webhook
{
    protected function input(): ?string
    {
        return request()?->getContent();
    }
}
