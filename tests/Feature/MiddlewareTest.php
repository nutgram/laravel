<?php

use Illuminate\Http\Request;
use Nutgram\Laravel\Middleware\ValidateWebAppData;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Web\WebAppData;
use SergiX44\Nutgram\Testing\FakeNutgram;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    $this->request = new Request();
});

it('validates web app data', function () {
    /** @var FakeNutgram $bot */
    $bot = app(Nutgram::class);

    $this->request->merge([
        'initData' => $bot->generateWebAppData([
            'foo' => 'bar',
            'auth_date' => time(),
        ])
    ]);

    $middleware = new ValidateWebAppData();
    $middleware->handle($this->request, function ($request) {
        expect($request->get('webapp'))->toBeInstanceOf(WebAppData::class);
    });
});

it('fails to validate web app data', function () {
    $middleware = new ValidateWebAppData();
    $middleware->handle($this->request, function ($request) {
    });
})->throws(HttpException::class);
