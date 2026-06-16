<x-filament-widgets::widget class="fi-wi-quick-actions hero-quick-actions-wrap">
    <x-filament::section heading="Quick actions" description="Jump to common operations tasks">
        <div class="hero-quick-grid">
            @foreach ($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="hero-quick-card {{ ($action['primary'] ?? false) ? 'is-primary' : '' }}"
                >
                    <span class="hero-quick-icon">
                        <x-filament::icon :icon="$action['icon']" style="width:1.25rem;height:1.25rem;" />
                    </span>
                    <span class="hero-quick-label">{{ $action['label'] }}</span>
                    <span class="hero-quick-desc">{{ $action['description'] }}</span>
                    <span class="hero-quick-arrow">Open &rarr;</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
