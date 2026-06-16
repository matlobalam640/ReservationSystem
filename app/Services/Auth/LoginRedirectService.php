<?php

namespace App\Services\Auth;

use App\Models\User;
use Filament\Facades\Filament;

class LoginRedirectService
{
    public function redirectFor(User $user): string
    {
        if ($user->hasRole('agency')) {
            return Filament::getPanel('agency')->getUrl();
        }

        if ($user->hasAnyRole(['admin', 'reservations', 'dispatch', 'accounting', 'check-in', 'medical-dispatch'])) {
            return Filament::getPanel('admin')->getUrl();
        }

        if ($user->passenger_id) {
            return route('account');
        }

        return url('/');
    }
}
