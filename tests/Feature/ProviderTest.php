<?php

use Illuminate\Support\Facades\File;
use Nutgram\Laravel\NutgramServiceProvider;

it('publishes files', function () {
    File::delete(base_path('routes/telegram.php'));

    $this->artisan('vendor:publish', [
        '--provider' => NutgramServiceProvider::class,
        '--tag' => 'nutgram',
    ]);

    expect(File::exists(config_path('nutgram.php')))->toBeTrue()
        ->and(File::exists(base_path('routes/telegram.php')))->toBeTrue();
});
