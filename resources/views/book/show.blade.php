@extends('layouts.public')

@section('title', 'Book '.$leg->routeLabel())

@section('content')
<p class="muted" style="margin-bottom:1rem;"><a href="{{ route('home') }}">&larr; All flights</a></p>

<h1 style="margin-bottom:0.25rem;">{{ $leg->routeLabel() }}</h1>
<p class="muted" style="margin-bottom:1.5rem;">
    {{ $leg->departure_at->format('l, F j Y \a\t g:i A') }} &bull;
    Aircraft {{ $leg->aircraft->tail_number }}
    @if($leg->base_price) &bull; <strong>${{ number_format($leg->base_price, 2) }}</strong>@endif
</p>

<form method="POST" action="{{ route('book.store', $leg) }}">
    @csrf

    <div class="card">
        <h3 style="margin-bottom:1rem;">Passenger Details</h3>
        <div class="grid-2">
            <div>
                <label for="first_name">First Name *</label>
                <input id="first_name" name="first_name" value="{{ old('first_name') }}" required>
            </div>
            <div>
                <label for="last_name">Last Name *</label>
                <input id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
            </div>
            <div>
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="{{ old('phone') }}">
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label for="weight_kg">Your Weight (kg) *</label>
                <input id="weight_kg" type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg') }}" required>
            </div>
            <div>
                <label for="baggage_weight_kg">Baggage Weight (kg)</label>
                <input id="baggage_weight_kg" type="number" step="0.1" name="baggage_weight_kg" value="{{ old('baggage_weight_kg', 0) }}">
            </div>
        </div>
        <div style="margin-top:1rem;">
            <label for="membership_code">HERO Membership Code (optional)</label>
            <input id="membership_code" name="membership_code" value="{{ old('membership_code') }}" placeholder="e.g. HERO-VIP-001">
            <p class="muted" style="margin-top:0.25rem;font-size:0.875rem;">Must match passenger name on membership. VIP members receive baggage discounts.</p>
        </div>
    </div>

    @if($addOns->isNotEmpty())
    <div class="card">
        <h3 style="margin-bottom:1rem;">Baggage &amp; Add-Ons</h3>
        @foreach($addOns as $addOn)
        <label style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
            <input type="checkbox" name="add_on_ids[]" value="{{ $addOn->id }}" @checked(collect(old('add_on_ids', []))->contains($addOn->id))>
            {{ $addOn->name }} — ${{ number_format($addOn->price, 2) }}
            @if($addOn->weight_kg > 0)<span class="muted">({{ $addOn->weight_kg }} kg)</span>@endif
        </label>
        @endforeach
    </div>
    @endif

    <div class="card">
        <h3 style="margin-bottom:1rem;">Select Seat</h3>
        @if($leg->seats->isEmpty())
            <p class="muted">No seats available on this flight.</p>
        @else
            <label for="seat_id">Seat *</label>
            <select id="seat_id" name="seat_id" required>
                <option value="">Choose a seat...</option>
                @foreach($leg->seats as $seat)
                    <option value="{{ $seat->id }}" @selected(old('seat_id') == $seat->id)>
                        Seat {{ $seat->seat_number }} ({{ $seat->seat_type->label() }})
                    </option>
                @endforeach
            </select>
        @endif
    </div>

    @error('seat_id')<div class="alert alert-error">{{ $message }}</div>@enderror

    <button type="submit" class="btn btn-primary" @disabled($leg->seats->isEmpty())>Confirm Booking</button>
</form>
@endsection
