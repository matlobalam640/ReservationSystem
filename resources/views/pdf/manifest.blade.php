<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Manifest {{ $date->format('Y-m-d') }}</title>
<style>body{font-family:sans-serif;font-size:11px} table{width:100%;border-collapse:collapse;margin-bottom:20px} td,th{border:1px solid #ccc;padding:4px}</style>
</head>
<body>
<h1>Flight Manifest — {{ $date->format('l, F j Y') }}</h1>
@foreach($legs as $leg)
<h2>{{ $leg->routeLabel() }} — {{ $leg->departure_at->format('g:i A') }} ({{ $leg->aircraft->tail_number }})</h2>
<table>
<tr><th>Seat</th><th>Passenger</th><th>Weight</th><th>Baggage</th></tr>
@foreach($leg->bookings as $booking)
@foreach($booking->bookingPassengers as $bp)
<tr>
<td>{{ $bp->seat?->seat_number ?? '—' }}</td>
<td>{{ $bp->passenger->fullName() }}</td>
<td>{{ $bp->actual_weight_kg ?? $bp->weight_kg ?? '—' }} kg</td>
<td>{{ $bp->actual_baggage_weight_kg ?? $bp->baggage_weight_kg ?? '—' }} kg</td>
</tr>
@endforeach
@endforeach
</table>
@endforeach
</body></html>
