<?php

use Illuminate\Http\Request;
use Nutgram\Laravel\Middleware\ValidateWebAppData;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Web\WebAppData;
use SergiX44\Nutgram\Testing\FakeNutgram;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    /** @var FakeNutgram $bot */
    $this->bot = app(Nutgram::class);
    $this->request = new Request();
});

it('validates web app data', function () {
    $this->request->merge([
        'initData' => $this->bot->generateWebAppData([
            'foo' => 'bar',
            'auth_date' => time(),
        ])
    ]);

    $middleware = new ValidateWebAppData($this->bot);
    $middleware->handle($this->request, function ($request) {
        expect($request->get('webAppData'))->toBeInstanceOf(WebAppData::class);
    });
});

it('fails to validate web app data', function () {
    $middleware = new ValidateWebAppData($this->bot);
    $middleware->handle($this->request, function ($request) {
    });
})->throws(HttpException::class);
