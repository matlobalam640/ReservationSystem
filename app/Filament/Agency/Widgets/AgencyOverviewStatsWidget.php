<?php

namespace App\Filament\Agency\Widgets;

use App\Enums\CommissionStatus;
use App\Filament\Agency\Resources\AgencyBookingResource;
use App\Filament\Agency\Resources\AgencyCommissionResource;
use App\Filament\Agency\Resources\AgencyFlightScheduleResource;
use App\Models\Booking;
use App\Models\CommissionLedger;
use App\Models\FlightLeg;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AgencyOverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Agency overview';

    protected ?string $description = 'Your bookings, commissions, and available schedules';

    protected int | array | null $columns = [
        'default' => 1,
        'sm' => 2,
        'lg' => 4,
    ];

    public function getSectionContentComponent(): Component
    {
        return Section::make()
            ->heading($this->getHeading())
            ->description($this->getDescription())
            ->icon(Heroicon::OutlinedPresentationChartLine)
            ->iconColor('primary')
            ->schema($this->getCachedStats())
            ->columns($this->getColumns())
            ->contained(true)
            ->gridContainer();
    }

    protected function getStats(): array
    {
        $agencyId = auth()->user()?->agency_id;

        if (! $agencyId) {
            return [
                Stat::make('Agency account', 'Not linked')
                    ->description('Contact HERO to link your agency profile')
                    ->color('danger'),
            ];
        }

        $bookingsCount = Booking::query()->where('agency_id', $agencyId)->count();
        $bookingsThisMonth = Booking::query()
            ->where('agency_id', $agencyId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingCommission = (float) CommissionLedger::query()
            ->where('agency_id', $agencyId)
            ->where('status', CommissionStatus::Pending)
            ->sum('agency_amount');

        $upcomingLegs = FlightLeg::query()
            ->bookableForAgency()
            ->count();

        return [
            Stat::make('Total bookings', (string) $bookingsCount)
                ->description("{$bookingsThisMonth} this month")
                ->url(AgencyBookingResource::getUrl('index')),
            Stat::make('Pending commission', '$'.number_format($pendingCommission, 2))
                ->description('Awaiting payout')
                ->url(AgencyCommissionResource::getUrl('index')),
            Stat::make('Bookable flights', (string) $upcomingLegs)
                ->description('Public & agency schedules')
                ->url(AgencyFlightScheduleResource::getUrl('index')),
            Stat::make('Agency', auth()->user()->agency?->name ?? '—')
                ->description(auth()->user()->agency?->payment_model?->label() ?? 'Payment model not set'),
        ];
    }
}
