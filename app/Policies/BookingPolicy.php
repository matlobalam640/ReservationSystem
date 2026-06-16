<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'reservations', 'dispatch', 'accounting', 'check-in', 'agency']);
    }

    public function view(User $user, Booking $booking): bool
    {
        if ($user->hasRole('agency')) {
            return $user->agency_id === $booking->agency_id;
        }

        return $user->hasAnyRole(['admin', 'reservations', 'dispatch', 'accounting', 'check-in']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'reservations', 'dispatch', 'agency', 'check-in']);
    }

    public function update(User $user, Booking $booking): bool
    {
        return $this->view($user, $booking);
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasAnyRole(['admin', 'reservations']);
    }
}
