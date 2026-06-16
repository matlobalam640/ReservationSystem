@extends('layouts.public')

@section('title', 'Check-In — '.$leg->routeLabel())

@section('content')
<p><a href="{{ route('check-in.index') }}">&larr; All legs</a></p>
<h1>{{ $leg->routeLabel() }}</h1>
<p class="muted">{{ $leg->departure_at->format('l, F j Y g:i A') }} &bull; {{ $leg->aircraft->tail_number }}</p>

<div class="card" style="{{ $load['is_overweight'] ? 'border-color:#fca5a5;background:#fef2f2;' : 'border-color:#bbf7d0;background:#f0fdf4;' }}">
    <strong>Aircraft Load: {{ $load['total_weight'] }} / {{ $load['max_weight'] ?: '?' }} kg ({{ $load['utilization_percent'] }}%)</strong>
    @foreach($load['alerts'] as $alert)<p style="color:#991b1b;">{{ $alert }}</p>@endforeach
</div>

@foreach($leg->bookings as $booking)
@foreach($booking->bookingPassengers as $bp)
<div class="card">
    <h3>{{ $bp->passenger->fullName() }} — Seat {{ $bp->seat?->seat_number ?? '?' }}</h3>
    @if($bp->checked_in_at)
        <p class="muted">Checked in at {{ $bp->checked_in_at->format('g:i A') }} — Actual: {{ $bp->actual_weight_kg }} kg</p>
    @else
    <form method="POST" action="{{ route('check-in.store', $bp) }}">
        @csrf
        <div class="grid-2">
            <div><label>Actual Weight (kg)</label><input type="number" step="0.1" name="actual_weight_kg" value="{{ $bp->weight_kg }}" required></div>
            <div><label>Actual Baggage (kg)</label><input type="number" step="0.1" name="actual_baggage_weight_kg" value="{{ $bp->baggage_weight_kg }}"></div>
        </div>
        <button type="submit" class="btn btn-primary">Check In</button>
    </form>
    @endif
</div>
@endforeach
@endforeach
@endsection
