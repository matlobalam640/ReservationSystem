<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Invoice {{ $invoice->invoice_number }}</title>
<style>body{font-family:sans-serif;font-size:12px} table{width:100%;border-collapse:collapse} td,th{border:1px solid #ccc;padding:6px}</style>
</head>
<body>
<h1>Invoice {{ $invoice->invoice_number }}</h1>
<p>Date: {{ $invoice->created_at->format('M j, Y') }} | Status: {{ $invoice->status->label() }}</p>
@if($invoice->booking)<p>Booking: {{ $invoice->booking->reference_number }}</p>@endif
<table>
<tr><th>Description</th><th>Amount</th></tr>
@foreach($invoice->lines as $line)
<tr><td>{{ $line->description }}</td><td>${{ number_format($line->amount, 2) }}</td></tr>
@endforeach
<tr><td><strong>Total</strong></td><td><strong>${{ number_format($invoice->total, 2) }}</strong></td></tr>
</table>
</body></html>
