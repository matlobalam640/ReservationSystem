<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\FlightLeg;
use Filament\Widgets\Widget;

class DashboardWelcomeWidget extends Widget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.dashboard-welcome';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'todayLegs' => FlightLeg::query()->whereDate('departure_at', today())->count(),
            'upcoming' => FlightLeg::query()->where('departure_at', '>', now())->count(),
            'bookingsToday' => Booking::query()->whereDate('created_at', today())->count(),
        ];
    }
}
