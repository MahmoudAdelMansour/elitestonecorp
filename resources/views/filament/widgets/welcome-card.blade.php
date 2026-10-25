<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">{{ __('admin.dashboard.welcome', ['name' => $name]) }}</x-slot>
        <x-slot name="description">{{ __('admin.dashboard.welcome_text') }}</x-slot>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(14rem,1fr));gap:.75rem">
            @foreach ($links as $link)
                <x-filament::button
                    tag="a"
                    :href="$link['url']"
                    :icon="$link['icon']"
                    :color="$link['color']"
                    :target="$link['external'] ? '_blank' : null"
                    size="xl"
                    style="justify-content:flex-start;min-height:3.5rem"
                >
                    {{ $link['label'] }}
                </x-filament::button>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
