<div>
    <x-filament::section heading="Recent Completed Legs">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:0.5rem;">Route</th>
                    <th style="padding:0.5rem;">Departure</th>
                    <th style="padding:0.5rem;">Block Hours</th>
                    <th style="padding:0.5rem;">Calculated Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLegs as $leg)
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:0.5rem;">{{ $leg['route'] }}</td>
                    <td style="padding:0.5rem;">{{ $leg['departure'] }}</td>
                    <td style="padding:0.5rem;">{{ $leg['hours'] }}</td>
                    <td style="padding:0.5rem;">${{ is_numeric($leg['cost']) ? number_format($leg['cost'], 2) : $leg['cost'] }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding:0.5rem;color:#6b7280;">No completed legs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-filament::section>

    <x-filament::section heading="Operator Invoice Discrepancies" class="mt-6">
        @forelse($openDiscrepancies as $invoice)
            <p style="margin-bottom:0.5rem;">
                {{ $invoice->operator->name }} — {{ $invoice->invoice_reference }}
                @if($invoice->open_discrepancies_count > 0)
                    <span style="color:#b45309;">({{ $invoice->open_discrepancies_count }} open)</span>
                @else
                    <span style="color:#15803d;">(reconciled)</span>
                @endif
            </p>
        @empty
            <p style="color:#6b7280;">No operator invoices recorded.</p>
        @endforelse
    </x-filament::section>
</div>
