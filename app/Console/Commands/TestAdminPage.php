<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestAdminPage extends Command
{
    protected $signature = 'test:admin-page {--live : Hit the public site over HTTP with login}';

    protected $description = 'Render /admin and report status, size, and asset URLs';

    public function handle(): int
    {
        if ($this->option('live')) {
            return $this->testLiveHttp();
        }

        return $this->testInternalKernel();
    }

    protected function testLiveHttp(): int
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $cookieJar = new \GuzzleHttp\Cookie\CookieJar;

        $loginPage = Http::withOptions(['cookies' => $cookieJar])->get($baseUrl.'/login');

        if (! $loginPage->successful()) {
            $this->error('Login page failed: '.$loginPage->status());

            return self::FAILURE;
        }

        if (! preg_match('/name="_token" value="([^"]+)"/', $loginPage->body(), $matches)) {
            $this->error('CSRF token not found on login page');

            return self::FAILURE;
        }

        $loginResponse = Http::withOptions(['cookies' => $cookieJar, 'allow_redirects' => false])
            ->asForm()
            ->post($baseUrl.'/login', [
                '_token' => $matches[1],
                'email' => 'admin@hero.ops',
                'password' => 'password',
            ]);

        $this->info('Login POST status: '.$loginResponse->status());

        $adminResponse = Http::withOptions(['cookies' => $cookieJar])
            ->get($baseUrl.'/admin');

        return $this->reportResponse($adminResponse->status(), $adminResponse->body(), storage_path('app/admin-live.html'));
    }

    protected function testInternalKernel(): int
    {
        $user = \App\Models\User::find(1);

        if (! $user) {
            $this->error('User #1 not found');

            return self::FAILURE;
        }

        \Illuminate\Support\Facades\Auth::login($user);

        $baseUrl = rtrim((string) config('app.url'), '/');
        \Illuminate\Support\Facades\URL::forceRootUrl($baseUrl);
        \Illuminate\Support\Facades\URL::forceScheme('https');

        $this->info('Route dashboard: '.(\Illuminate\Support\Facades\Route::has('filament.admin.pages.dashboard') ? 'yes' : 'no'));

        $request = \Illuminate\Http\Request::create(
            $baseUrl.'/admin',
            'GET',
            [],
            [],
            [],
            [
                'HTTP_HOST' => parse_url($baseUrl, PHP_URL_HOST),
                'HTTPS' => 'on',
                'HTTP_X_FORWARDED_PROTO' => 'https',
            ],
        );

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request);
        $content = $response->getContent() ?: '';

        $this->reportResponse($response->getStatusCode(), $content, storage_path('app/admin-test.html'));
        $kernel->terminate($request, $response);

        return self::SUCCESS;
    }

    protected function reportResponse(int $status, string $content, string $path): int
    {
        file_put_contents($path, $content);

        $this->info('Status: '.$status);
        $this->info('Size: '.strlen($content));
        $this->info('Saved: '.$path);
        $this->info('Scripts: '.substr_count(strtolower($content), '<script'));
        $this->info('Body length: '.strlen(strip_tags($content)));
        $this->info('Has fi-sidebar: '.(str_contains($content, 'fi-sidebar') ? 'yes' : 'no'));
        $this->info('Has wire:snapshot: '.(str_contains($content, 'wire:snapshot') ? 'yes' : 'no'));
        $this->info('Has localhost: '.(str_contains($content, 'localhost') ? 'yes' : 'no'));

        if (strlen($content) < 500) {
            $this->warn('Body preview: '.$content);
        } else {
            $this->line(substr($content, 0, 400));
        }

        return self::SUCCESS;
    }
}
