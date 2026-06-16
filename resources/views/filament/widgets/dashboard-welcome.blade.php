<x-filament-widgets::widget class="hero-dash-welcome-widget">
    <div class="hero-dash-welcome">
        <div class="hero-dash-welcome-inner">
            <div>
                <h2>Operations Dashboard</h2>
                <p>
                    Bridging critical medical &amp; emergency infrastructure gaps in Haiti —
                    manage helicopter reservations, dispatch, and passenger operations.
                </p>
            </div>
            <div class="hero-dash-welcome-meta">
                <span class="hero-dash-pill">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="h-4 w-4" style="width:1rem;height:1rem;" />
                    {{ now()->format('l, M j Y') }}
                </span>
                <span class="hero-dash-pill">
                    <strong>{{ $todayLegs }}</strong> legs today
                </span>
                <span class="hero-dash-pill">
                    <strong>{{ $upcoming }}</strong> upcoming
                </span>
                <span class="hero-dash-pill">
                    <strong>{{ $bookingsToday }}</strong> bookings today
                </span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
