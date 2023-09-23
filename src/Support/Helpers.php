<?php

namespace Nutgram\Laravel\Support;

use SergiX44\Nutgram\Telegram\Web\WebAppData;


if (!function_exists(__NAMESPACE__.'\webAppData')) {
    function webAppData(): ?WebAppData
    {
        return request()?->get('webAppData');
    }
}
