@extends('layouts.public')

@section('title', 'Generate Manifest')

@section('content')
<h1>Generate Manifest</h1>
<form method="POST" action="{{ route('manifest.download') }}">
    @csrf
    <div class="card">
        <label>Aircraft</label>
        <select name="aircraft_id" required>
            @foreach($aircraft as $a)<option value="{{ $a->id }}">{{ $a->tail_number }} — {{ $a->aircraft_type }}</option>@endforeach
        </select>
        <label>Date</label>
        <input type="date" name="date" value="{{ today()->format('Y-m-d') }}" required>
        <button type="submit" class="btn btn-primary" style="margin-top:1rem;">Download PDF</button>
    </div>
</form>
@endsection
