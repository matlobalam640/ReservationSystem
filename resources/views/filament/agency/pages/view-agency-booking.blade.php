<x-filament-panels::page>
    <x-filament::section heading="Booking summary">
        <dl style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin:0;">
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Reference</dt>
                <dd style="font-weight:700;margin:0;">{{ $record->reference_number }}</dd>
            </div>
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Route</dt>
                <dd style="font-weight:600;margin:0;">{{ $record->flightLeg->routeLabel() }}</dd>
            </div>
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Departure</dt>
                <dd style="margin:0;">{{ $record->flightLeg->departure_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Status</dt>
                <dd style="margin:0;">{{ $record->status->value }}</dd>
            </div>
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Total</dt>
                <dd style="font-weight:700;margin:0;">${{ number_format((float) $record->total_amount, 2) }}</dd>
            </div>
            <div>
                <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Payment</dt>
                <dd style="margin:0;">{{ $record->payment_status->value }}</dd>
            </div>
        </dl>
    </x-filament::section>

    <x-filament::section heading="Passengers" style="margin-top:1rem;">
        @foreach($record->bookingPassengers as $bp)
            <div style="padding:0.75rem 0;border-bottom:1px solid #e2e8f0;">
                <strong>{{ $bp->passenger->first_name }} {{ $bp->passenger->last_name }}</strong>
                <div style="font-size:0.875rem;color:#64748b;margin-top:0.25rem;">
                    Seat {{ $bp->seat?->seat_number ?? '—' }}
                    @if($bp->weight_kg) · {{ $bp->weight_kg }} kg @endif
                    @if($bp->passenger->email) · {{ $bp->passenger->email }} @endif
                </div>
            </div>
        @endforeach
    </x-filament::section>

    @if($record->bookingAddOns->isNotEmpty())
        <x-filament::section heading="Add-ons" style="margin-top:1rem;">
            @foreach($record->bookingAddOns as $addon)
                <div style="display:flex;justify-content:space-between;padding:0.5rem 0;">
                    <span>{{ $addon->addOn->name }}</span>
                    <span>${{ number_format((float) $addon->total_price, 2) }}</span>
                </div>
            @endforeach
        </x-filament::section>
    @endif

    @php $commission = $record->commissionLedger->first(); @endphp
    @if($commission)
        <x-filament::section heading="Commission" style="margin-top:1rem;">
            <dl style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin:0;">
                <div>
                    <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Your commission</dt>
                    <dd style="font-weight:700;color:#059669;margin:0;">${{ number_format((float) $commission->agency_amount, 2) }}</dd>
                </div>
                <div>
                    <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">HERO share</dt>
                    <dd style="margin:0;">${{ number_format((float) $commission->hero_amount, 2) }}</dd>
                </div>
                <div>
                    <dt style="font-size:0.75rem;color:#64748b;margin-bottom:0.25rem;">Status</dt>
                    <dd style="margin:0;">{{ $commission->status->value }}</dd>
                </div>
            </dl>
        </x-filament::section>
    @endif
</x-filament-panels::page>
