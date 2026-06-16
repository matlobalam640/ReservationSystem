<?php

namespace App\Filament\Agency\Pages;

use App\Filament\Agency\Widgets\AgencyOverviewStatsWidget;
use App\Filament\Agency\Widgets\AgencyWelcomeWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class AgencyDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    public function getTitle(): string | Htmlable
    {
        return '';
    }

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 4,
        ];
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            AgencyWelcomeWidget::class,
            AgencyOverviewStatsWidget::class,
        ];
    }
}
