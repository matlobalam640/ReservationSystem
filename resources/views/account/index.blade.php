@extends('layouts.public')

@section('title', 'My Account')

@section('content')
<h1>My Account</h1>
<p>Welcome, {{ $user->name }}</p>

@if($membership)
<div class="card">
    <h3>HERO Membership — {{ $membership->plan_level }}</h3>
    <p class="muted">Code: {{ $membership->member_code }} | Expires: {{ $membership->expires_at?->format('M j, Y') ?? 'N/A' }}</p>
</div>
@endif

<div class="card">
    <h3>Loyalty Points: {{ $loyaltyPoints }}</h3>
</div>

<h2 style="margin:1.5rem 0 0.75rem;">Upcoming Flights</h2>
@if($upcomingBookings->isEmpty())
<p class="muted">No upcoming flights linked to your account.</p>
@else
@foreach($upcomingBookings as $bp)
<div class="card">
    <strong>{{ $bp->booking->flightLeg->routeLabel() }}</strong>
    <p class="muted">{{ $bp->booking->flightLeg->departure_at->format('M j, Y g:i A') }} — Seat {{ $bp->seat?->seat_number ?? '?' }}</p>
    <p class="muted">Ref: {{ $bp->booking->reference_number }}</p>
</div>
@endforeach
@endif
@endsection
