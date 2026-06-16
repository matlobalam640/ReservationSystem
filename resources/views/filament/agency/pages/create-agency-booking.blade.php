<x-filament-panels::page>
    <x-filament::section heading="Passenger &amp; Flight">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Flight Leg</label>
                <select wire:model.live="flight_leg_id" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
                    <option value="">Select leg...</option>
                    @foreach($this->getLegOptions() as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Seat</label>
                <select wire:model="seat_id" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
                    <option value="">Select seat...</option>
                    @foreach($this->getSeatOptions() as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">First Name</label>
                <input type="text" wire:model.live="first_name" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Last Name</label>
                <input type="text" wire:model.live="last_name" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Email</label>
                <input type="email" wire:model.live="email" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Phone</label>
                <input type="text" wire:model="phone" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Weight (kg)</label>
                <input type="number" step="0.1" wire:model="weight_kg" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">HERO Membership Code</label>
                <input type="text" wire:model.live="membership_code" placeholder="Optional" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
        </div>

        @if(count($this->getAddOnOptions()) > 0)
            <div style="margin-top:1rem;">
                <label style="display:block;font-size:0.875rem;margin-bottom:0.5rem;">Add-ons</label>
                <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                    @foreach($this->getAddOnOptions() as $id => $name)
                        <label style="display:flex;align-items:center;gap:0.35rem;font-size:0.875rem;">
                            <input type="checkbox" wire:model.live="add_on_ids" value="{{ $id }}">
                            {{ $name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        @if($this->quote)
            <div style="margin-top:1.25rem;padding:1rem;border-radius:0.5rem;background:#f8fafc;border:1px solid #e2e8f0;">
                <div style="font-weight:700;margin-bottom:0.5rem;">Booking estimate</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:0.75rem;font-size:0.875rem;">
                    <div>
                        <span style="color:#64748b;">Ticket total</span><br>
                        <strong style="font-size:1.1rem;">${{ number_format($this->quote['total'], 2) }}</strong>
                    </div>
                    <div>
                        <span style="color:#64748b;">Your commission</span><br>
                        <strong style="font-size:1.1rem;color:#059669;">${{ number_format($this->quote['commission']['agency_amount'], 2) }}</strong>
                    </div>
                    <div>
                        <span style="color:#64748b;">HERO share</span><br>
                        <strong>${{ number_format($this->quote['commission']['hero_amount'], 2) }}</strong>
                    </div>
                    @if($this->quote['payment_model'])
                        <div>
                            <span style="color:#64748b;">Payment model</span><br>
                            <strong>{{ $this->quote['payment_model'] }}</strong>
                        </div>
                    @endif
                </div>
                @if($this->quote['commission']['rule_name'])
                    <p style="margin:0.75rem 0 0;font-size:0.8rem;color:#64748b;">Rule: {{ $this->quote['commission']['rule_name'] }}</p>
                @endif
            </div>
        @endif

        <div style="margin-top:1rem;">
            <x-filament::button wire:click="submit">Create Booking</x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
