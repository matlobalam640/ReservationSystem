<?php

namespace App\Filament\Widgets;

use App\Enums\InvoiceStatus;
use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Flights\FlightResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\MedevacCases\MedevacCaseResource;
use App\Models\Booking;
use App\Models\FlightLeg;
use App\Models\Invoice;
use App\Models\MedevacCase;
use App\Services\Operations\LoadCalculatorService;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OpsOverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Today at a glance';

    protected ?string $description = 'Live operations snapshot for HERO helicopter reservations';

    protected int | array | null $columns = [
        'default' => 1,
        'sm' => 2,
        'lg' => 4,
        'xl' => 4,
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
        $todayLegs = FlightLeg::query()->whereDate('departure_at', today())->count();
        $upcomingToday = FlightLeg::query()
            ->where('departure_at', '>', now())
            ->whereDate('departure_at', today())
            ->count();

        $bookingsToday = Booking::query()->whereDate('created_at', today())->count();
        $revenueToday = (float) Booking::query()->whereDate('created_at', today())->sum('total_amount');

        $bookingTrend = collect(range(6, 0))
            ->map(fn (int $daysAgo) => Booking::query()->whereDate('created_at', today()->subDays($daysAgo))->count())
            ->all();

        [$overweight, $nearCapacity] = $this->loadAlerts();

        $openInvoices = Invoice::query()
            ->whereIn('status', [InvoiceStatus::Unpaid, InvoiceStatus::Partial, InvoiceStatus::Overdue])
            ->count();

        $medevacCases = MedevacCase::query()->count();

        return [
            Stat::make('Today\'s Legs', $todayLegs)
                ->description($upcomingToday.' still to depart')
                ->descriptionIcon(Heroicon::OutlinedPaperAirplane)
                ->icon(Heroicon::OutlinedCalendarDays)
                ->color('primary')
                ->chart($bookingTrend)
                ->url(FlightResource::getUrl('index')),

            Stat::make('Bookings Today', $bookingsToday)
                ->description('$'.number_format($revenueToday, 0).' revenue')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->icon(Heroicon::OutlinedTicket)
                ->color('success')
                ->chart($bookingTrend)
                ->url(BookingResource::getUrl('index')),

            Stat::make('Open Invoices', $openInvoices)
                ->description('Awaiting payment')
                ->descriptionIcon(Heroicon::OutlinedDocumentText)
                ->icon(Heroicon::OutlinedCreditCard)
                ->color($openInvoices > 0 ? 'warning' : 'success')
                ->url(InvoiceResource::getUrl('index')),

            Stat::make('Medevac Cases', $medevacCases)
                ->description('Active case records')
                ->descriptionIcon(Heroicon::OutlinedHeart)
                ->icon(Heroicon::OutlinedLifebuoy)
                ->color($medevacCases > 0 ? 'danger' : 'gray')
                ->url(MedevacCaseResource::getUrl('index')),

            Stat::make('Overweight Alerts', $overweight)
                ->description('Today\'s legs over max load')
                ->icon(Heroicon::OutlinedScale)
                ->color($overweight > 0 ? 'danger' : 'success'),

            Stat::make('High Load (85%+)', $nearCapacity)
                ->description('Near capacity today')
                ->icon(Heroicon::OutlinedExclamationTriangle)
                ->color($nearCapacity > 0 ? 'warning' : 'success'),

            Stat::make('Upcoming Legs', FlightLeg::query()->where('departure_at', '>', now())->count())
                ->description('All future departures')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->icon(Heroicon::OutlinedMap)
                ->color('info')
                ->url(FlightResource::getUrl('index')),

            Stat::make('Total Bookings', Booking::query()->count())
                ->description('All time in system')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->icon(Heroicon::OutlinedQueueList)
                ->color('gray')
                ->url(BookingResource::getUrl('index')),
        ];
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function loadAlerts(): array
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

        return [$overweight, $nearCapacity];
    }
}
