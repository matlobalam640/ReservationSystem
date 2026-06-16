@extends('layouts.public')

@section('title', 'HERO Reservation System')
@section('main_class', '')
@section('main_wrapper', '')

@section('content')
<section class="page-hero" style="background-image: url('{{ asset(config('hero.branding.hero_banner')) }}');">
    <img src="{{ asset(config('hero.branding.logo')) }}" alt="HERO Client Rescue" class="hero-logo">
    <h1>Book Your Flight</h1>
    <p class="page-hero-lead">
        Bridging critical medical &amp; emergency infrastructure gaps in Haiti — book helicopter shuttles across Haiti
        and the Dominican Republic, including Pétion-Ville ⇄ Santo Domingo, Cap-Haïtien connections, and charter services.
    </p>
    <div class="hero-actions">
        <a href="#flights" class="btn btn-primary">View Available Flights</a>
        @auth
            @if(auth()->user()->hasRole('agency'))
                <a href="{{ url('/agency') }}" class="btn btn-outline">Agency Portal</a>
            @elseif(auth()->user()->hasAnyRole(['admin', 'reservations', 'dispatch', 'accounting', 'check-in', 'medical-dispatch']))
                <a href="{{ url('/admin') }}" class="btn btn-outline">Admin Portal</a>
            @elseif(auth()->user()->passenger_id)
                <a href="{{ route('account') }}" class="btn btn-outline">My Account</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Staff / Agency Login</a>
        @endauth
    </div>

    <form method="GET" action="{{ route('home') }}#flights" class="search-form">
        <p class="search-form-label">Search &amp; book a seat</p>
        <div class="search-grid">
            <div>
                <label for="origin">From</label>
                <select id="origin" name="origin">
                    <option value="">Any origin</option>
                    @foreach($origins as $code)
                        <option value="{{ $code }}" @selected($selectedOrigin === $code)>
                            {{ $locations[$code] ?? strtoupper($code) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="destination">To</label>
                <select id="destination" name="destination">
                    <option value="">Any destination</option>
                    @foreach($destinations as $code)
                        <option value="{{ $code }}" @selected($selectedDestination === $code)>
                            {{ $locations[$code] ?? strtoupper($code) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date">Travel Date &amp; Time</label>
                <input
                    id="date"
                    type="text"
                    name="date"
                    value="{{ $selectedDate }}"
                    placeholder="Select date and time..."
                    autocomplete="off"
                    data-min-date="{{ now()->format('Y-m-d') }}"
                >
            </div>
            <div class="search-actions">
                <button type="submit" class="btn btn-primary">Search Flights</button>
                @if($searched)
                    <a href="{{ route('home') }}" class="btn btn-outline-light">Clear</a>
                @endif
            </div>
        </div>
    </form>
</section>

<section class="section section-light">
    <div class="container">
        <div class="mission-banner" style="background-image: url('{{ asset(config('hero.branding.hero_mission')) }}');">
            <div>
                <h2>One Mission, One Team.</h2>
                <p>
                    HERO Client Rescue S.A. has been on a mission to save lives and deliver hope across Haiti and beyond
                    since 2014 — through air and ground emergency response, medevac coordination, and helicopter reservations.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <h2>Featured Destinations</h2>
        <p class="section-intro muted">Popular routes available to book through HERO.</p>
        <div class="featured-grid">
            @foreach($featuredRoutes as $route)
                <article class="featured-card {{ $route['badge'] ? 'is-new' : '' }}">
                    @if($route['badge'])
                        <div class="featured-badge">{{ $route['badge'] }}</div>
                    @endif
                    <div class="featured-body">
                        <h3>{{ $route['title'] }}</h3>
                        <p class="muted">{{ $route['description'] }}</p>
                        <p class="featured-price">
                            ${{ number_format($route['from_price'], 2) }}
                            <span>/ per person</span>
                        </p>
                        <div class="featured-actions">
                            <a href="{{ route('home', ['origin' => $route['origin'], 'destination' => $route['destination']]) }}#flights" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </article>
            @endforeach
            <article class="featured-card">
                <div class="featured-body">
                    <h3>Destinations Across Haiti</h3>
                    <p class="muted">Multiple domestic routes and charter options to locations throughout Haiti — contact reservations for custom itineraries.</p>
                    <p class="featured-price">Custom <span>/ quote on request</span></p>
                    <div class="featured-actions">
                        <a href="#flights" class="btn btn-secondary">View Flights</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<section id="flights" class="section section-light">
    <div class="container">
        <div class="results-panel">
            <div class="results-header">
                <div class="results-header-text">
                    <h2>{{ $searched ? 'Search Results' : 'Upcoming Flights' }}</h2>
                    <p class="muted">Select a scheduled leg below to reserve your seat online.</p>
                </div>
                @if($legs->isNotEmpty())
                    <div class="results-count">
                        <span>{{ $legs->count() }}</span>
                        {{ Str::plural('flight', $legs->count()) }} found
                    </div>
                @endif
            </div>

            @if($searched && ($selectedOrigin || $selectedDestination || $selectedDate))
                <div class="search-filters">
                    @if($selectedOrigin)
                        <span class="filter-chip">From: {{ $locations[$selectedOrigin] ?? $selectedOrigin }}</span>
                    @endif
                    @if($selectedDestination)
                        <span class="filter-chip">To: {{ $locations[$selectedDestination] ?? $selectedDestination }}</span>
                    @endif
                    @if($selectedDate)
                        <span class="filter-chip">Date: {{ \Carbon\Carbon::parse($selectedDate)->format(str_contains($selectedDate, ':') ? 'M j, Y g:i A' : 'M j, Y') }}</span>
                    @endif
                </div>
            @endif

            @if($legs->isEmpty())
                <div class="results-empty">
                    <div class="results-empty-icon" aria-hidden="true">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
                        </svg>
                    </div>
                    <h3>{{ $searched ? 'No matching flights' : 'No upcoming flights' }}</h3>
                    <p>
                        @if($searched)
                            We couldn't find any legs for your current search. Try a different date, route, or browse all available flights.
                        @else
                            There are no public flights scheduled right now. Please check back soon or contact our reservations team.
                        @endif
                    </p>
                    <div class="results-empty-actions">
                        @if($searched)
                            <a href="{{ route('home') }}#flights" class="btn btn-primary">View All Flights</a>
                        @endif
                        <a href="#" onclick="window.scrollTo({ top: 0, behavior: 'smooth' }); return false;" class="btn btn-secondary">Modify Search</a>
                    </div>
                </div>
            @else
                <div class="results-list">
                    @foreach($legs as $leg)
                        @php $seatsLow = $leg->available_seats_count <= 2; @endphp
                        <article class="flight-result-card">
                            <div class="flight-route-badge">
                                <span class="code">{{ strtoupper($leg->origin) }}</span>
                                <span class="arrow">↓</span>
                                <span class="code">{{ strtoupper($leg->destination) }}</span>
                            </div>
                            <div class="flight-result-body">
                                <h3>{{ $leg->routeLabel() }}</h3>
                                <div class="flight-meta">
                                    <span class="flight-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $leg->departure_at->format('D, M j Y') }}
                                    </span>
                                    <span class="flight-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        {{ $leg->departure_at->format('g:i A') }}
                                    </span>
                                    <span class="flight-meta-item">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                                        {{ $leg->aircraft->tail_number }}
                                    </span>
                                    <span class="flight-meta-item {{ $seatsLow ? 'seats-low' : '' }}">
                                        {{ $leg->available_seats_count }} {{ Str::plural('seat', $leg->available_seats_count) }} left
                                    </span>
                                </div>
                            </div>
                            <div class="flight-result-action">
                                @if($leg->base_price)
                                    <span class="flight-price-tag">
                                        ${{ number_format($leg->base_price, 0) }}
                                        <small>per person</small>
                                    </span>
                                @endif
                                <a href="{{ route('book.show', $leg) }}" class="btn btn-primary">Select &amp; Book</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="medevac-banner">
            <div>
                <h2>Medevac Request</h2>
                <p>
                    HERO coordinates medical evacuation for urgent patient transport.
                    Medevac flights are handled by our dispatch team — not booked as standard public seats.
                </p>
                <p class="muted" style="color:#94a3b8;margin-top:1rem;">
                    For emergency medevac, contact HERO operations directly at
                    <strong style="color:#fff;">{{ config('hero.contact.phone_hero') }}</strong> (24/7).
                    Helicopter reservations: <strong style="color:#fff;">{{ config('hero.contact.phone_helo') }}</strong>.
                </p>
                <div style="margin-top:1.25rem;">
                    <a href="{{ route('login') }}" class="btn btn-primary">Staff / Dispatch Login</a>
                </div>
            </div>
            <div>
                <img
                    src="{{ asset(config('hero.branding.medevac')) }}"
                    alt="HERO Helicopter MEDEVAC"
                    class="medevac-banner-image"
                    loading="lazy"
                >
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <h2>More Services</h2>
        <div class="info-grid">
            <div class="info-card">
                <h3>Scheduled Shuttles</h3>
                <p>Regular helicopter connections including Pétion-Ville ⇄ Santo Domingo and Cap-Haïtien routes.</p>
            </div>
            <div class="info-card">
                <h3>HERO 911 &amp; SafeRide</h3>
                <p>Emergency response and secure transport services as part of the broader HERO &amp; HALO ecosystem.</p>
            </div>
            <div class="info-card">
                <h3>Charter &amp; Private</h3>
                <p>Custom routes across Haiti and the Caribbean for business, tourism, and government missions.</p>
            </div>
            <div class="info-card">
                <h3>Agency &amp; Member Rates</h3>
                <p>Travel partners and HERO VIP members book with verified benefits, commissions, and account billing.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <h2>How people book with HERO</h2>
        <div class="channels-grid">
            <div class="channel-card">
                <h3>Public Website</h3>
                <p>Passengers browse public flight legs and reserve seats online with baggage add-ons and seat selection.</p>
            </div>
            <div class="channel-card">
                <h3>Travel Agencies</h3>
                <p>Agency partners book on behalf of clients, track commissions, and manage agency-visible schedules.</p>
            </div>
            <div class="channel-card">
                <h3>Phone &amp; Counter</h3>
                <p>Reservations staff and check-in handle walk-ins, phone bookings, and last-minute seat sales.</p>
            </div>
            <div class="channel-card">
                <h3>HERO Members</h3>
                <p>VIP members receive identity-verified benefits on eligible flights — not reusable coupon codes.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container contact-block">
        <h2>{{ $contact['chat_label'] ?? 'Contact us' }}</h2>
        <p class="muted" style="margin:0.75rem auto 1rem;max-width:560px;">
            Questions about routes, pricing, or group travel? Our reservations team is here to help.
        </p>
        <p><strong>{{ $contact['address'] ?? '' }}</strong></p>
        <p class="muted" style="margin-top:0.5rem;">
            <a href="{{ config('hero.contact.website') }}" target="_blank" rel="noopener" style="color:#f59e0b;font-weight:600;text-decoration:none;">
                heroclientrescue.com
            </a>
        </p>
        <p style="margin-top:1rem;">
            <a href="{{ route('login') }}">Staff / Agency Login</a>
        </p>
    </div>
</section>

<footer class="site-footer">
    <img src="{{ asset(config('hero.branding.logo')) }}" alt="HERO Client Rescue" class="footer-logo">
    <p>© {{ date('Y') }} HERO Client Rescue S.A. — Helicopter reservations portal.</p>
    <p style="margin-top:0.5rem;">
        <a href="{{ config('hero.branding.source_url') }}" target="_blank" rel="noopener">heroclientrescue.com</a>
    </p>
</footer>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('date');
            if (! input || typeof flatpickr === 'undefined') return;

            const savedValue = (input.value || '').trim();

            flatpickr(input, {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                altInput: true,
                altFormat: 'F j, Y \\at h:i K',
                altInputClass: 'flatpickr-input',
                minDate: input.dataset.minDate || 'today',
                defaultHour: 8,
                defaultMinute: 0,
                minuteIncrement: 15,
                disableMobile: true,
                defaultDate: savedValue || null,
                onReady: function (_dates, _str, instance) {
                    instance.calendarContainer.classList.add('hero-flatpickr');
                    if (savedValue) {
                        instance.setDate(savedValue, false);
                    }
                },
            });
        });
    </script>
@endpush
