<?php

namespace Nutgram\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;

class ValidateWebAppData
{
    public function __construct(protected Nutgram $bot)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $initData = $request->input('initData', '');
            $data = $this->bot->validateWebAppData($initData);

            $request->attributes->add(['webAppData' => $data]);
            return $next($request);
        } catch (InvalidDataException) {
            $this->handleInvalidData($request, $next);
        }
    }

    protected function handleInvalidData(Request $request, Closure $next): void
    {
        abort(403);
    }
}
