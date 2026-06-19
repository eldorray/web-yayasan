@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'centered' => false,
])

<section {{ $attributes->merge(['class' => 'relative overflow-hidden']) }} style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
    <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
    <div class="absolute -top-20 right-0 w-72 h-72 rounded-full blur-3xl opacity-20" style="background: var(--color-gold-500);" aria-hidden="true"></div>

    <div @class([
        'relative public-container py-14 lg:py-20',
        'text-center' => $centered,
    ])>
        @if ($eyebrow)
            <p @class(['eyebrow rise rise-1', 'mx-auto' => $centered]) style="color: var(--color-gold-500);">{{ $eyebrow }}</p>
        @endif
        <h1 @class([
            'font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight rise rise-2',
            'mt-3' => $eyebrow,
        ])>{{ $title }}</h1>
        @if ($description)
            <p @class([
                'mt-4 text-white/90 text-base sm:text-lg leading-relaxed rise rise-3',
                'max-w-2xl mx-auto' => $centered,
                'max-w-2xl' => ! $centered,
            ])>{{ $description }}</p>
        @endif
        @if ($slot->isNotEmpty())
            <div class="mt-8 rise rise-4">{{ $slot }}</div>
        @endif
    </div>
</section>
