<div>
    {{-- Page header --}}
    <section class="relative text-white py-20 overflow-hidden" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold tracking-[0.18em] uppercase mb-3 rise rise-1" style="color: var(--color-gold-500);">Tentang Kami</p>
            <h1 class="font-display text-4xl md:text-5xl font-extrabold rise rise-2">{{ $settings->name }}</h1>
            <p class="mt-4 text-white/85 max-w-2xl mx-auto rise rise-3">{{ $settings->tagline }}</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        {{-- Visi & Misi --}}
        @if ($settings->vision || $settings->mission)
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @if ($settings->vision)
            <div class="bg-white rounded-2xl p-8 shadow-soft border-t-4" style="border-color: var(--brand-600);">
                <h2 class="text-xl font-bold text-ink-900 mb-3">Visi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->vision }}</p>
            </div>
            @endif
            @if ($settings->mission)
            <div class="bg-white rounded-2xl p-8 shadow-soft border-t-4" style="border-color: var(--color-gold-500);">
                <h2 class="text-xl font-bold text-ink-900 mb-3">Misi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->mission }}</p>
            </div>
            @endif
        </section>
        @endif

        {{-- Sejarah --}}
        @if ($settings->history)
        <section>
            <h2 class="text-2xl font-bold text-ink-900 mb-4">Sejarah</h2>
            <div class="prose prose-ink max-w-none bg-white rounded-2xl p-8 shadow-soft">
                {!! $settings->history !!}
            </div>
        </section>
        @endif
    </div>
</div>
