@props(['value', 'label', 'icon' => null])

<div {{ $attributes->merge(['class' => 'public-card card-lift p-6 text-center cursor-default']) }}>
    @if ($icon)
        <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" style="background-color: var(--brand-50); color: var(--brand-700);">
            <x-public.icon :name="$icon" class="w-5 h-5" />
        </div>
    @endif
    <p class="font-display text-3xl sm:text-4xl font-extrabold text-gradient-gold">{{ $value }}</p>
    <p class="text-xs sm:text-sm text-ink-600 mt-2 uppercase tracking-wide font-medium">{{ $label }}</p>
</div>
