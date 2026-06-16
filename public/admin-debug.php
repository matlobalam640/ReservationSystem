<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

$request = Illuminate\Http\Request::create(
    'https://snow-emu-551412.hostingersite.com/admin',
    'GET',
    server: [
        'HTTP_HOST' => 'snow-emu-551412.hostingersite.com',
        'HTTPS' => 'on',
        'HTTP_X_FORWARDED_PROTO' => 'https',
    ],
);

$app->instance('request', $request);
$app->boot();

header('Content-Type: text/plain; charset=utf-8');

echo 'runningInConsole='.(app()->runningInConsole() ? 'yes' : 'no')."\n";
echo 'app.url='.config('app.url')."\n";
echo 'asset(app.js)='.asset('js/filament/filament/app.js')."\n";
echo 'asset(livewire)='.asset('vendor/livewire/livewire.min.js')."\n";

Illuminate\Support\Facades\Auth::loginUsingId(1);

$response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle(
    Illuminate\Http\Request::create(
        'https://snow-emu-551412.hostingersite.com/admin',
        'GET',
        server: [
            'HTTP_HOST' => 'snow-emu-551412.hostingersite.com',
            'HTTPS' => 'on',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ],
    )
);

echo 'admin_status='.$response->getStatusCode()."\n";
echo 'admin_size='.strlen($response->getContent() ?: '')."\n";

preg_match_all('#src="(https?://[^"]+)"#', $response->getContent() ?: '', $matches);

foreach (array_slice(array_unique($matches[1] ?? []), 0, 8) as $url) {
    echo $url."\n";
}
