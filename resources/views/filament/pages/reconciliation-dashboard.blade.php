<div class="hero-recon-page">
    <div class="hero-recon-stats">
        <div class="hero-recon-stat">
            <span class="hero-recon-stat-value">{{ $stats['logged_legs'] }}</span>
            <span class="hero-recon-stat-label">Legs with time logs</span>
        </div>
        <div class="hero-recon-stat">
            <span class="hero-recon-stat-value">{{ $stats['completed_legs'] }}</span>
            <span class="hero-recon-stat-label">Completed / departed</span>
        </div>
        <div class="hero-recon-stat">
            <span class="hero-recon-stat-value">{{ $stats['operator_invoices'] }}</span>
            <span class="hero-recon-stat-label">Operator invoices</span>
        </div>
        <div class="hero-recon-stat {{ $stats['open_discrepancies'] > 0 ? 'is-alert' : '' }}">
            <span class="hero-recon-stat-value">{{ $stats['open_discrepancies'] }}</span>
            <span class="hero-recon-stat-label">Open discrepancies</span>
        </div>
    </div>

    <div class="hero-recon-grid">
        <x-filament::section
            heading="Flight legs &amp; calculated costs"
            description="All scheduled legs with internal block hours and operator cost estimates"
        >
            <div class="hero-recon-table-wrap">
                <table class="hero-recon-table">
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Departure</th>
                            <th>Status</th>
                            <th>Operator</th>
                            <th>Block hours</th>
                            <th>Calculated cost</th>
                            <th>Time log</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($legs as $leg)
                            <tr>
                                <td><strong>{{ $leg['route'] }}</strong></td>
                                <td>{{ $leg['departure'] }}</td>
                                <td>
                                    <span class="hero-recon-badge hero-recon-badge-{{ $leg['status_color'] }}">
                                        {{ $leg['status'] }}
                                    </span>
                                </td>
                                <td>{{ $leg['operator'] }}</td>
                                <td>
                                    @if($leg['hours'] !== null)
                                        {{ number_format((float) $leg['hours'], 2) }} h
                                    @else
                                        <span class="hero-recon-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if(is_numeric($leg['cost']) && $leg['cost'] > 0)
                                        ${{ number_format($leg['cost'], 2) }}
                                    @else
                                        <span class="hero-recon-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ $leg['edit_url'] }}" class="hero-recon-link">
                                        {{ $leg['has_time_log'] ? 'View log' : 'Add log' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="hero-recon-empty">No flight legs found. Create flights under Operations → Flights.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section
            heading="Operator invoices"
            description="Invoices received from helicopter operators for reconciliation"
        >
            <div class="hero-recon-table-wrap">
                <table class="hero-recon-table">
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Reference</th>
                            <th>Period</th>
                            <th>Hours</th>
                            <th>Cost</th>
                            <th>Status</th>
                            <th>Discrepancies</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operatorInvoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice['operator'] }}</strong></td>
                                <td>
                                    <a href="{{ $invoice['edit_url'] }}" class="hero-recon-link">{{ $invoice['reference'] }}</a>
                                </td>
                                <td>{{ $invoice['period'] }}</td>
                                <td>{{ number_format((float) $invoice['hours'], 2) }} h</td>
                                <td>${{ number_format((float) $invoice['cost'], 2) }}</td>
                                <td>
                                    <span class="hero-recon-badge hero-recon-badge-gray">{{ ucfirst($invoice['status']) }}</span>
                                </td>
                                <td>
                                    @if($invoice['open_discrepancies'] > 0)
                                        <span class="hero-recon-badge hero-recon-badge-warning">{{ $invoice['open_discrepancies'] }} open</span>
                                    @elseif($invoice['total_discrepancies'] > 0)
                                        <span class="hero-recon-badge hero-recon-badge-success">Resolved</span>
                                    @else
                                        <span class="hero-recon-muted">None</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="hero-recon-empty">
                                    No operator invoices yet.
                                    <a href="{{ \App\Filament\Resources\OperatorInvoices\OperatorInvoiceResource::getUrl('create') }}" class="hero-recon-link">Add invoice</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</div>
