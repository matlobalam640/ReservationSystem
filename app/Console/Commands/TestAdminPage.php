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

        $this->info('Route admin-dashboard: '.(Route::has('filament.admin.pages.admin-dashboard') ? 'yes' : 'no'));

        $request = Request::create('/admin', 'GET');
        $response = $kernel->handle($request);
        $content = $response->getContent() ?: '';

        $this->info('Status: '.$response->getStatusCode());
        $this->info('Size: '.strlen($content));
        $this->line(substr($content, 0, 1500));

        $kernel->terminate($request, $response);

        return self::SUCCESS;
    }
}
