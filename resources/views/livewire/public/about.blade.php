<div>
    <x-public.page-hero
        eyebrow="Tentang Kami"
        :title="$settings->name"
        :description="$settings->tagline"
        centered
    />

    <div class="public-container-narrow public-section space-y-12">
        @if ($settings->vision || $settings->mission)
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($settings->vision)
            <div class="public-card p-8 border-t-4 card-lift" style="border-color: var(--brand-600);">
                <div class="w-10 h-10 rounded-xl mb-4 flex items-center justify-center" style="background-color: var(--brand-50); color: var(--brand-700);">
                    <x-public.icon name="sparkles" class="w-5 h-5" />
                </div>
                <h2 class="font-display text-xl font-bold text-ink-900 mb-3">Visi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->vision }}</p>
            </div>
            @endif
            @if ($settings->mission)
            <div class="public-card p-8 border-t-4 card-lift" style="border-color: var(--color-gold-500);">
                <div class="w-10 h-10 rounded-xl mb-4 flex items-center justify-center" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">
                    <x-public.icon name="school" class="w-5 h-5" />
                </div>
                <h2 class="font-display text-xl font-bold text-ink-900 mb-3">Misi</h2>
                <p class="text-ink-700 leading-relaxed">{{ $settings->mission }}</p>
            </div>
            @endif
        </section>
        @endif

        @if ($settings->history)
        <section>
            <x-public.section-header eyebrow="Perjalanan" title="Sejarah Yayasan" />
            <div class="public-card p-8 sm:p-10">
                <div class="prose prose-ink max-w-none text-ink-700 leading-relaxed">
                    {!! $settings->history !!}
                </div>
            </div>
        </section>
        @endif

        @if ($settings->established_year || $settings->students_count)
        <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if ($settings->established_year)
                <x-public.stat-card :value="$settings->established_year" label="Tahun Berdiri" icon="calendar" />
            @endif
            @if ($settings->students_count)
                <x-public.stat-card :value="number_format($settings->students_count, 0, ',', '.').'+'" label="Siswa Aktif" icon="users" />
            @endif
        </section>
        @endif
    </div>

    <x-public.cta-banner
        title="Kenali sekolah binaan kami"
        description="Setiap jenjang dirancang untuk membentuk generasi berakhlak mulia dan berprestasi."
    >
        <a href="{{ route('public.schools.index') }}" wire:navigate class="public-btn public-btn-gold">
            Jelajahi Sekolah
            <x-public.icon name="arrow-right" class="w-4 h-4" />
        </a>
    </x-public.cta-banner>
</div>
