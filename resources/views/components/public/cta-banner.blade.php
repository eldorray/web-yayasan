@props([
    'title',
    'description' => null,
])

<section {{ $attributes->merge(['class' => 'public-container pb-12 lg:pb-16']) }}>
    <div class="relative overflow-hidden rounded-[2rem] p-8 sm:p-12 text-center text-white shadow-soft"
         style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="pattern-stars absolute inset-0 opacity-50" aria-hidden="true"></div>
        <div class="relative max-w-2xl mx-auto">
            <h2 class="font-display text-2xl sm:text-3xl font-bold">{{ $title }}</h2>
            @if ($description)
                <p class="mt-3 text-white/90 text-sm sm:text-base">{{ $description }}</p>
            @endif
            @if ($slot->isNotEmpty())
                <div class="mt-6 flex flex-wrap justify-center gap-3">{{ $slot }}</div>
            @endif
        </div>
    </div>
</section>
