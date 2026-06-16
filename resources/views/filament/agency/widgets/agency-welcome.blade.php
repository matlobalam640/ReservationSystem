<x-filament-widgets::widget class="hero-dash-welcome-widget">
    <div class="hero-dash-welcome hero-agency-welcome">
        <div class="hero-dash-welcome-inner">
            <div>
                <h2>Welcome, {{ explode(' ', $userName)[0] }}</h2>
                <p>
                    Book flights on behalf of your clients, track commissions, and browse agency-visible schedules —
                    all from your {{ $agencyName }} portal.
                </p>
            </div>
            <div class="hero-dash-welcome-meta">
                @if($paymentModel)
                    <span class="hero-dash-pill"><strong>Payment:</strong> {{ $paymentModel }}</span>
                @endif
                <span class="hero-dash-pill"><strong>Portal</strong> Agency</span>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
