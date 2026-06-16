<?php

namespace App\Filament\Widgets;

use App\Models\FlightLeg;
use App\Services\Operations\LoadCalculatorService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LoadAlertsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $calculator = app(LoadCalculatorService::class);
        $overweight = 0;
        $nearCapacity = 0;

        $legs = FlightLeg::query()
            ->whereDate('departure_at', today())
            ->with('aircraft')
            ->get();

        foreach ($legs as $leg) {
            $load = $calculator->calculateForLeg($leg);
            if ($load['is_overweight']) {
                $overweight++;
            } elseif ($load['utilization_percent'] >= 85) {
                $nearCapacity++;
            }
        }

        return [
            Stat::make('Overweight Alerts', $overweight)->color($overweight ? 'danger' : 'success'),
            Stat::make('High Load (85%+)', $nearCapacity)->color($nearCapacity ? 'warning' : 'success'),
        ];
    }
}
