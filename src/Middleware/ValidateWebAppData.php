<?php

namespace Nutgram\Laravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;

class ValidateWebAppData
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $initData = $request->input('initData', '');
            $data = app(Nutgram::class)->validateWebAppData($initData);

            $request->attributes->add(['webapp' => $data]);
            return $next($request);
        } catch (InvalidDataException) {
            abort(403);
        }
    }
}
