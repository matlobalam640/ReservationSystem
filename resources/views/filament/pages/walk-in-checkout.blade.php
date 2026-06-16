<x-filament-panels::page>
    <x-filament::section heading="Find Booking">
        <div style="display:flex;gap:0.5rem;align-items:end;">
            <div style="flex:1;">
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Reference Number</label>
                <input type="text" wire:model="reference" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <x-filament::button wire:click="search">Search</x-filament::button>
        </div>
    </x-filament::section>

    @if($booking)
    <x-filament::section heading="Booking {{ $booking->reference_number }}" class="mt-4">
        <p><strong>{{ $booking->flightLeg->routeLabel() }}</strong> — {{ $booking->flightLeg->departure_at->format('M j, Y g:i A') }}</p>
        <p>Passenger: {{ $booking->bookingPassengers->first()?->passenger?->fullName() ?? '—' }}</p>
        <p>Total: ${{ number_format($booking->total_amount, 2) }} ({{ $booking->payment_status->label() }})</p>

        <div style="margin-top:1rem;display:flex;gap:0.5rem;align-items:end;">
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Amount</label>
                <input type="number" step="0.01" wire:model="amount" style="padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Method</label>
                <select wire:model="method" style="padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
                    @foreach(\App\Enums\PaymentMethod::cases() as $pm)
                        <option value="{{ $pm->value }}">{{ $pm->label() }}</option>
                    @endforeach
                </select>
            </div>
            <x-filament::button wire:click="recordPayment" color="success">Record Payment</x-filament::button>
        </div>
    </x-filament::section>
    @endif
</x-filament-panels::page>
