<x-filament-widgets::widget class="fi-wi-quick-actions">
    <x-filament::section heading="Quick actions" description="Jump to common operations tasks">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
            @foreach ($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    @class([
                        'group flex flex-col rounded-xl border p-4 transition duration-200',
                        'border-amber-200 bg-amber-50/70 hover:border-amber-400 hover:bg-amber-50' => $action['color'] === 'amber',
                        'border-slate-200 bg-slate-50/70 hover:border-slate-400 hover:bg-slate-50' => $action['color'] === 'slate',
                        'border-blue-200 bg-blue-50/70 hover:border-blue-400 hover:bg-blue-50' => $action['color'] === 'blue',
                        'border-emerald-200 bg-emerald-50/70 hover:border-emerald-400 hover:bg-emerald-50' => $action['color'] === 'emerald',
                        'border-violet-200 bg-violet-50/70 hover:border-violet-400 hover:bg-violet-50' => $action['color'] === 'violet',
                        'border-rose-200 bg-rose-50/70 hover:border-rose-400 hover:bg-rose-50' => $action['color'] === 'rose',
                        'border-cyan-200 bg-cyan-50/70 hover:border-cyan-400 hover:bg-cyan-50' => $action['color'] === 'cyan',
                    ])
                >
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-black/5">
                        @switch($action['icon'])
                            @case('ticket')
                                <x-filament::icon icon="heroicon-o-ticket" class="h-5 w-5 text-amber-600" />
                                @break
                            @case('credit-card')
                                <x-filament::icon icon="heroicon-o-credit-card" class="h-5 w-5 text-slate-700" />
                                @break
                            @case('clipboard')
                                <x-filament::icon icon="heroicon-o-clipboard-document-check" class="h-5 w-5 text-blue-600" />
                                @break
                            @case('document')
                                <x-filament::icon icon="heroicon-o-document-text" class="h-5 w-5 text-emerald-600" />
                                @break
                            @case('map')
                                <x-filament::icon icon="heroicon-o-map" class="h-5 w-5 text-violet-600" />
                                @break
                            @case('scale')
                                <x-filament::icon icon="heroicon-o-scale" class="h-5 w-5 text-rose-600" />
                                @break
                            @case('globe')
                                <x-filament::icon icon="heroicon-o-globe-alt" class="h-5 w-5 text-cyan-600" />
                                @break
                        @endswitch
                    </span>
                    <span class="text-sm font-semibold text-gray-950 group-hover:text-gray-900">{{ $action['label'] }}</span>
                    <span class="mt-1 text-xs text-gray-500">{{ $action['description'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
