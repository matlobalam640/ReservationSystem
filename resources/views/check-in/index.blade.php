@extends('layouts.public')

@section('title', 'Check-In')

@section('content')
<h1>Check-In</h1>
<p class="muted" style="margin-bottom:1rem;">Select a flight leg to check in passengers.</p>
@foreach($legs as $leg)
<div class="card">
    <h3>{{ $leg->routeLabel() }}</h3>
    <p class="muted">{{ $leg->departure_at->format('M j, Y g:i A') }}</p>
    <a href="{{ route('check-in.show', $leg) }}" class="btn btn-primary">Open Check-In</a>
</div>
@endforeach
@endsection
