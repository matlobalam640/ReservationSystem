@extends('layouts.public')

@section('title', 'Book a Flight — HERO Reservation System')

@section('content')
<h1 style="margin-bottom:0.5rem;">Available Flights</h1>
<p class="muted" style="margin-bottom:1.5rem;">Select a public flight leg to reserve your seat.</p>

@if($legs->isEmpty())
    <div class="card">
        <p>No public flights are available right now. Please check back later or contact reservations.</p>
    </div>
@else
    @foreach($legs as $leg)
        <div class="card">
            <h2>{{ $leg->routeLabel() }}</h2>
            <p class="muted">
                {{ $leg->departure_at->format('D, M j Y — g:i A') }}
                &bull; {{ $leg->aircraft->tail_number }} ({{ $leg->aircraft->aircraft_type }})
                &bull; {{ $leg->seats()->where('is_available', true)->count() }} seats left
            </p>
            @if($leg->base_price)
                <p style="margin:0.5rem 0;font-weight:600;">${{ number_format($leg->base_price, 2) }} per seat</p>
            @endif
            <a href="{{ route('book.show', $leg) }}" class="btn btn-primary" style="margin-top:0.75rem;">Select &amp; Book</a>
        </div>
    @endforeach
@endif
@endsection
