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
                <input type="text" wire:model="first_name" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Last Name</label>
                <input type="text" wire:model="last_name" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Email</label>
                <input type="email" wire:model="email" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Phone</label>
                <input type="text" wire:model="phone" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
            <div>
                <label style="display:block;font-size:0.875rem;margin-bottom:0.25rem;">Weight (kg)</label>
                <input type="number" step="0.1" wire:model="weight_kg" style="width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:0.375rem;">
            </div>
        </div>
        <div style="margin-top:1rem;">
            <x-filament::button wire:click="submit">Create Booking</x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
