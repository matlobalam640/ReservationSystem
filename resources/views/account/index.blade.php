@extends('layouts.public')

@section('title', 'My Account — HERO Reservation System')
@section('main_class', '')
@section('main_wrapper', '')

@push('styles')
<style>
    .account-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        color: #fff;
        padding: 2.5rem 2rem 3rem;
        position: relative;
        overflow: hidden;
    }
    .account-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(245, 158, 11, 0.18) 0%, transparent 45%);
        pointer-events: none;
    }
    .account-hero-inner {
        position: relative;
        z-index: 1;
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
    }
    .account-hero-user {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .account-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(145deg, #f59e0b, #d97706);
        color: #0f172a;
        font-size: 1.5rem;
        font-weight: 800;
        display: grid;
        place-items: center;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.35);
        flex-shrink: 0;
    }
    .account-hero h1 {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        margin-bottom: 0.25rem;
        letter-spacing: -0.02em;
    }
    .account-hero-sub {
        color: #cbd5e1;
        font-size: 0.95rem;
    }
    .account-hero-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .account-hero .btn-outline {
        border-color: rgba(255,255,255,0.35);
        color: #fff;
    }
    .account-hero .btn-outline:hover {
        border-color: #f59e0b;
        color: #f59e0b;
    }
    .account-body {
        max-width: 1100px;
        margin: -1.75rem auto 0;
        padding: 0 2rem 3rem;
        position: relative;
        z-index: 2;
    }
    .account-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .account-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        padding: 1.25rem 1.35rem;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
        position: relative;
        overflow: hidden;
    }
    .account-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #f59e0b, transparent);
    }
    .account-stat-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 0.35rem;
    }
    .account-stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
    }
    .account-stat-meta {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.35rem;
    }
    .account-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .account-grid { grid-template-columns: 1fr; }
        .account-body { padding: 0 1.25rem 2.5rem; }
        .account-hero { padding: 2rem 1.25rem 2.5rem; }
    }
    .account-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .account-panel-header {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .account-panel-header h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .account-panel-body { padding: 1.25rem 1.35rem; }
    .account-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #fcd34d;
    }
    .membership-card {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .membership-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .membership-detail dt {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .membership-detail dd {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        margin: 0.15rem 0 0;
    }
    .account-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #64748b;
    }
    .account-empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        background: #f1f5f9;
        display: grid;
        place-items: center;
        font-size: 1.5rem;
    }
    .account-empty h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0.35rem;
    }
    .flight-ticket {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1.1rem 1.2rem;
        margin-bottom: 0.85rem;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .flight-ticket:last-child { margin-bottom: 0; }
    .flight-ticket:hover {
        border-color: rgba(245, 158, 11, 0.45);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.1);
    }
    .flight-ticket-route {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .flight-ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.25rem;
        font-size: 0.85rem;
        color: #64748b;
    }
    .flight-ticket-meta strong { color: #334155; }
    .flight-ticket-ref {
        margin-top: 0.65rem;
        padding-top: 0.65rem;
        border-top: 1px dashed #e2e8f0;
        font-size: 0.8rem;
        color: #94a3b8;
        font-family: ui-monospace, monospace;
    }
    .account-quick-links {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        margin-top: 1rem;
    }
    .account-quick-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem;
        border-radius: 0.65rem;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #0f172a;
        font-weight: 600;
        font-size: 0.9rem;
        transition: border-color 0.2s, background 0.2s;
    }
    .account-quick-link:hover {
        border-color: #f59e0b;
        background: #fffbeb;
        color: #b45309;
    }
    .account-quick-link span:last-child { color: #f59e0b; }
</style>
@endpush

@section('content')
<section class="account-hero">
    <div class="account-hero-inner">
        <div class="account-hero-user">
            <div class="account-avatar">{{ $initials ?: '?' }}</div>
            <div>
                <h1>Welcome back, {{ explode(' ', $user->name)[0] }}</h1>
                <p class="account-hero-sub">{{ $user->email }}</p>
            </div>
        </div>
        <div class="account-hero-actions">
            <a href="{{ route('home') }}#flights" class="btn btn-primary">Book a Flight</a>
            <a href="{{ route('home') }}" class="btn btn-outline">Back to Home</a>
        </div>
    </div>
</section>

<div class="account-body">
    <div class="account-stats">
        <div class="account-stat">
            <div class="account-stat-label">Upcoming flights</div>
            <div class="account-stat-value">{{ $upcomingBookings->count() }}</div>
            <div class="account-stat-meta">Scheduled departures</div>
        </div>
        <div class="account-stat">
            <div class="account-stat-label">Loyalty points</div>
            <div class="account-stat-value">{{ number_format($loyaltyPoints) }}</div>
            <div class="account-stat-meta">Earn more with each flight</div>
        </div>
        <div class="account-stat">
            <div class="account-stat-label">Total bookings</div>
            <div class="account-stat-value">{{ $totalBookings }}</div>
            <div class="account-stat-meta">On your HERO account</div>
        </div>
    </div>

    <div class="account-grid">
        <div>
            <div class="account-panel">
                <div class="account-panel-header">
                    <h2>HERO Membership</h2>
                    @if($membership)
                        <span class="account-badge">{{ $membership->plan_level }}</span>
                    @endif
                </div>
                <div class="account-panel-body">
                    @if($membership)
                        <div class="membership-card">
                            <dl class="membership-detail">
                                <div>
                                    <dt>Member code</dt>
                                    <dd>{{ $membership->member_code }}</dd>
                                </div>
                                <div>
                                    <dt>Expires</dt>
                                    <dd>{{ $membership->expires_at?->format('M j, Y') ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt>Plan</dt>
                                    <dd>{{ $membership->plan_level }}</dd>
                                </div>
                                <div>
                                    <dt>Status</dt>
                                    <dd style="color:#059669;">Active</dd>
                                </div>
                            </dl>
                            <p class="muted" style="font-size:0.85rem;line-height:1.5;">
                                VIP members enjoy baggage discounts and priority handling on HERO helicopter services.
                            </p>
                        </div>
                    @else
                        <div class="account-empty" style="padding:1.5rem 0;">
                            <p>No active HERO membership linked to this account.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="account-quick-links">
                <a href="{{ route('home') }}#flights" class="account-quick-link">
                    <span>Search available flights</span>
                    <span>&rarr;</span>
                </a>
                <a href="{{ route('home') }}#contact" class="account-quick-link">
                    <span>Contact HERO support</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <div class="account-panel">
            <div class="account-panel-header">
                <h2>Upcoming Flights</h2>
                @if($upcomingBookings->isNotEmpty())
                    <span class="muted" style="font-size:0.85rem;">{{ $upcomingBookings->count() }} scheduled</span>
                @endif
            </div>
            <div class="account-panel-body">
                @if($upcomingBookings->isEmpty())
                    <div class="account-empty">
                        <div class="account-empty-icon">✈</div>
                        <h3>No upcoming flights</h3>
                        <p>Book your next helicopter shuttle across Haiti and the Dominican Republic.</p>
                        <a href="{{ route('home') }}#flights" class="btn btn-primary" style="margin-top:1rem;display:inline-block;">Browse Flights</a>
                    </div>
                @else
                    @foreach($upcomingBookings as $bp)
                        @php $leg = $bp->booking->flightLeg; @endphp
                        <div class="flight-ticket">
                            <div class="flight-ticket-route">{{ $leg->routeLabel() }}</div>
                            <div class="flight-ticket-meta">
                                <span><strong>Departs</strong> {{ $leg->departure_at->format('D, M j, Y') }}</span>
                                <span><strong>Time</strong> {{ $leg->departure_at->format('g:i A') }}</span>
                                <span><strong>Seat</strong> {{ $bp->seat?->seat_number ?? 'TBD' }}</span>
                                @if($leg->aircraft?->tail_number)
                                    <span><strong>Aircraft</strong> {{ $leg->aircraft->tail_number }}</span>
                                @endif
                            </div>
                            <div class="flight-ticket-ref">Ref: {{ $bp->booking->reference_number }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
