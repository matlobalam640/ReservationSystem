@extends('layouts.public')

@section('title', 'Booking Confirmed')

@section('content')
<div class="card" style="border-color:#bbf7d0;background:#f0fdf4;">
    <h1 style="color:#166534;margin-bottom:0.5rem;">Booking Confirmed</h1>
    <p class="muted">Reference: <strong>{{ $booking->reference_number }}</strong></p>
</div>

@php $bp = $booking->bookingPassengers->first(); @endphp

<div class="card">
    <h3>Flight</h3>
    <p><strong>{{ $booking->flightLeg->routeLabel() }}</strong></p>
    <p class="muted">{{ $booking->flightLeg->departure_at->format('l, F j Y \a\t g:i A') }}</p>
    <p class="muted">Aircraft: {{ $booking->flightLeg->aircraft->tail_number }}</p>
</div>

@if($bp)
<div class="card">
    <h3>Passenger</h3>
    <p>{{ $bp->passenger->fullName() }}</p>
    @if($bp->seat)<p class="muted">Seat {{ $bp->seat->seat_number }}</p>@endif
    @if($bp->weight_kg)<p class="muted">Weight: {{ $bp->weight_kg }} kg</p>@endif
</div>
@endif

@if($booking->bookingAddOns->isNotEmpty())
<div class="card">
    <h3>Add-Ons</h3>
    <ul>
        @foreach($booking->bookingAddOns as $bao)
            <li>{{ $bao->addOn->name }} — ${{ number_format($bao->total_price, 2) }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <p>Total: <strong>${{ number_format($booking->total_amount, 2) }}</strong></p>
    <p class="muted">Payment status: {{ $booking->payment_status->label() }}</p>
    <p class="muted" style="margin-top:0.5rem;">Payment will be collected at check-in or per your booking terms.</p>
</div>

@if(! $booking->notes || ! str_contains($booking->notes, 'HERO member'))
<div class="card" style="border-color:#fde68a;background:#fffbeb;">
    <h3>Join HERO Membership</h3>
    <p class="muted">Save on baggage and enjoy priority access. Ask about VIP plans at check-in or visit our office.</p>
</div>
@endif

<a href="{{ route('home') }}" class="btn btn-secondary">Book Another Flight</a>
<a href="{{ route('home') }}" class="btn btn-primary" style="margin-left:0.5rem;">Home</a>
@endsection
