<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BookingsByChannelChart;
use App\Filament\Widgets\BookingsTrendChart;
use App\Filament\Widgets\DashboardWelcomeWidget;
use App\Filament\Widgets\OpsOverviewStatsWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentBookingsTableWidget;
use App\Filament\Widgets\UpcomingFlightsTableWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class AdminDashboard extends BaseDashboard
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
            'lg' => 6,
        ];
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            DashboardWelcomeWidget::class,
            OpsOverviewStatsWidget::class,
            QuickActionsWidget::class,
            BookingsTrendChart::class,
            BookingsByChannelChart::class,
            UpcomingFlightsTableWidget::class,
            RecentBookingsTableWidget::class,
        ];
    }
}
