<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class TestAdminPage extends Command
{
    protected $signature = 'test:admin-page';

    protected $description = 'Render /admin as user #1 and report status';

    public function handle(Kernel $kernel): int
    {
        $user = User::find(1);

        if (! $user) {
            $this->error('User #1 not found');

            return self::FAILURE;
        }

        Auth::login($user);

        $baseUrl = rtrim((string) config('app.url'), '/');
        \Illuminate\Support\Facades\URL::forceRootUrl($baseUrl);
        \Illuminate\Support\Facades\URL::forceScheme('https');

        $this->info('Route admin-dashboard: '.(Route::has('filament.admin.pages.admin-dashboard') ? 'yes' : 'no'));

        $request = Request::create(
            config('app.url').'/admin',
            'GET',
            [],
            [],
            [],
            [
                'HTTP_HOST' => parse_url((string) config('app.url'), PHP_URL_HOST),
                'HTTPS' => 'on',
                'HTTP_X_FORWARDED_PROTO' => 'https',
            ],
        );
        $response = $kernel->handle($request);
        $content = $response->getContent() ?: '';

        $this->info('Status: '.$response->getStatusCode());
        $this->info('Size: '.strlen($content));

        $path = storage_path('app/admin-test.html');
        file_put_contents($path, $content);
        $this->info('Saved: '.$path);
        $this->info('Scripts: '.substr_count(strtolower($content), '<script'));
        $this->info('Livewire refs: '.substr_count(strtolower($content), 'livewire'));

        $this->line(substr($content, 0, 800));

        $kernel->terminate($request, $response);

        return self::SUCCESS;
    }
}
