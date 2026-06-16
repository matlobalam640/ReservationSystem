<?php

namespace App\Filament\Widgets;

use App\Models\FlightLeg;
use App\Services\Operations\LoadCalculatorService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayFlightsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayLegs = FlightLeg::query()->whereDate('departure_at', today())->count();
        $upcoming = FlightLeg::query()->where('departure_at', '>', now())->whereDate('departure_at', today())->count();

        return [
            Stat::make('Today\'s Legs', $todayLegs)->description('Scheduled today'),
            Stat::make('Upcoming Today', $upcoming)->description('Still to depart'),
        ];
    }
}
