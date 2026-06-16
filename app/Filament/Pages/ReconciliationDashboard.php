<?php

namespace App\Filament\Pages;

use App\Enums\LegStatus;
use App\Filament\Resources\LegTimeLogs\LegTimeLogResource;
use App\Filament\Resources\OperatorInvoices\OperatorInvoiceResource;
use App\Models\FlightLeg;
use App\Models\OperatorInvoice;
use App\Services\Finance\ReconciliationService;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

class ReconciliationDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static string|UnitEnum|null $navigationGroup = 'Reconciliation';

    protected static ?string $navigationLabel = 'Overview';

    protected static ?string $title = 'Reconciliation Dashboard';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.reconciliation-dashboard';

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Compare internal leg time logs with operator invoices and spot discrepancies.';
    }

    public function getViewData(): array
    {
        $reconciliation = app(ReconciliationService::class);

        $legs = FlightLeg::query()
            ->with(['timeLog', 'aircraft.operator'])
            ->orderByDesc('departure_at')
            ->limit(20)
            ->get()
            ->map(fn (FlightLeg $leg) => [
                'id' => $leg->id,
                'route' => $leg->routeLabel(),
                'departure' => $leg->departure_at->format('M j, Y g:i A'),
                'status' => $leg->status->label(),
                'status_color' => $leg->status->color(),
                'operator' => $leg->aircraft?->operator?->name ?? '—',
                'hours' => $leg->timeLog?->block_time_hours ?? $leg->timeLog?->flight_time_hours,
                'cost' => $leg->timeLog?->calculated_cost ?? $reconciliation->calculateLegCost($leg),
                'has_time_log' => $leg->timeLog !== null,
                'edit_url' => $leg->timeLog
                    ? LegTimeLogResource::getUrl('edit', ['record' => $leg->timeLog])
                    : LegTimeLogResource::getUrl('create').'?flight_leg_id='.$leg->id,
            ]);

        $operatorInvoices = OperatorInvoice::query()
            ->with(['operator'])
            ->withCount([
                'discrepancies as open_discrepancies_count' => fn ($q) => $q->where('resolved', false),
                'discrepancies as total_discrepancies_count',
            ])
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn (OperatorInvoice $invoice) => [
                'id' => $invoice->id,
                'operator' => $invoice->operator?->name ?? '—',
                'reference' => $invoice->invoice_reference,
                'period' => $invoice->period_start?->format('M j').' – '.$invoice->period_end?->format('M j, Y'),
                'hours' => $invoice->total_hours,
                'cost' => $invoice->total_cost,
                'status' => $invoice->status,
                'open_discrepancies' => $invoice->open_discrepancies_count,
                'total_discrepancies' => $invoice->total_discrepancies_count,
                'edit_url' => OperatorInvoiceResource::getUrl('edit', ['record' => $invoice]),
            ]);

        $stats = [
            'logged_legs' => FlightLeg::query()->whereHas('timeLog')->count(),
            'completed_legs' => FlightLeg::query()->whereIn('status', [LegStatus::Completed, LegStatus::Departed])->count(),
            'open_discrepancies' => \App\Models\ReconciliationDiscrepancy::query()->where('resolved', false)->count(),
            'operator_invoices' => OperatorInvoice::query()->count(),
        ];

        return compact('legs', 'operatorInvoices', 'stats');
    }
}
