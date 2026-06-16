<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

Illuminate\Support\Facades\Auth::loginUsingId(1);

$request = Illuminate\Http\Request::create(
    'https://snow-emu-551412.hostingersite.com/admin',
    'GET',
    server: [
        'HTTP_HOST' => 'snow-emu-551412.hostingersite.com',
        'HTTPS' => 'on',
        'HTTP_X_FORWARDED_PROTO' => 'https',
        'REQUEST_URI' => '/admin',
    ],
);

$response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
$content = $response->getContent() ?: '';

header('Content-Type: text/plain; charset=utf-8');

echo 'status='.$response->getStatusCode()."\n";
echo 'size='.strlen($content)."\n\n";

preg_match_all('#(?:src|href)="(https?://[^"]+)"#', $content, $matches);

foreach (array_unique($matches[1] ?? []) as $url) {
    echo $url."\n";
}
