<?php

namespace App\Filament\Pages;

use App\Filament\Agency\Widgets\AgencyOverviewStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;

class AgencyDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            AgencyOverviewStatsWidget::class,
        ];
    }
}
