<?php

namespace Nutgram\Laravel\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nutgram\Laravel\RunningMode\WebhookResponse;

/**
 * Lets $bot->asResponse() answer the Telegram webhook request with a Bot API call.
 * Without it on the webhook route, asResponse() falls back to a normal API request.
 */
class SendWebhookResponse
{
    public function handle(Request $request, Closure $next): mixed
    {
        $webhookResponse = new WebhookResponse();
        $request->attributes->set(WebhookResponse::class, $webhookResponse);

        $response = $next($request);

        return $webhookResponse->call === null ? $response : new JsonResponse($webhookResponse->call);
    }
}
