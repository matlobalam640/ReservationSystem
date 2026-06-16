<?php

namespace App\Filament\Pages;

use App\Models\FlightLeg;
use App\Models\OperatorInvoice;
use App\Services\Finance\ReconciliationService;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ReconciliationDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'Reconciliation';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.reconciliation-dashboard';

    public function getViewData(): array
    {
        $reconciliation = app(ReconciliationService::class);

        $recentLegs = FlightLeg::query()
            ->where('departure_at', '<', now())
            ->with(['timeLog', 'aircraft.operator'])
            ->latest('departure_at')
            ->limit(10)
            ->get()
            ->map(fn (FlightLeg $leg) => [
                'route' => $leg->routeLabel(),
                'departure' => $leg->departure_at->format('M j, Y'),
                'hours' => $leg->timeLog?->block_time_hours ?? '—',
                'cost' => $leg->timeLog?->calculated_cost ?? $reconciliation->calculateLegCost($leg),
            ]);

        $openDiscrepancies = OperatorInvoice::query()
            ->withCount(['discrepancies as open_discrepancies_count' => fn ($q) => $q->where('resolved', false)])
            ->latest()
            ->limit(5)
            ->get();

        return compact('recentLegs', 'openDiscrepancies');
    }
}
