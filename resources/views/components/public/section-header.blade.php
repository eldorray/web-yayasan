@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'href' => null,
    'linkLabel' => 'Lihat semua',
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 lg:mb-10']) }}>
    <div>
        @if ($eyebrow)
            <p class="eyebrow text-brand-700">{{ $eyebrow }}</p>
        @endif
        <h2 class="font-display text-2xl sm:text-3xl font-bold text-ink-900 mt-3">{{ $title }}</h2>
        @if ($description)
            <p class="text-ink-600 text-sm sm:text-base mt-2 max-w-xl">{{ $description }}</p>
        @endif
    </div>
    @if ($href)
        <a href="{{ $href }}" wire:navigate class="public-link motion-tap shrink-0 self-start sm:self-auto">
            {{ $linkLabel }}
            <x-public.icon name="arrow-right" class="w-4 h-4" />
        </a>
    @endif
</div>
