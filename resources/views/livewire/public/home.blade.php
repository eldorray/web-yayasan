<div>
    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
        <div class="relative grid grid-cols-1 md:grid-cols-2 gap-8 items-center rounded-[2rem] overflow-hidden shadow-soft"
             style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            {{-- Ambient Islamic lattice --}}
            <div class="pattern-stars absolute inset-0 opacity-70" aria-hidden="true"></div>
            {{-- Gold glow --}}
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: var(--color-gold-500);" aria-hidden="true"></div>

            <div class="relative p-8 md:p-14 text-white">
                <p class="eyebrow rise rise-1" style="color: var(--color-gold-500);">Yayasan Pendidikan Islam</p>
                <h1 class="font-display text-4xl md:text-5xl font-extrabold leading-[1.08] mt-4 rise rise-2">
                    {{ $settings->name }}
                </h1>
                <p class="mt-5 text-white/85 text-lg max-w-md rise rise-3">{{ $settings->tagline }}</p>
                <div class="mt-8 flex flex-wrap gap-3 rise rise-4">
                    <a href="{{ route('public.schools.index') }}"
                       class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-bold transition-transform hover:scale-[1.03]"
                       style="background-color: var(--color-gold-500); color: var(--brand-900);">
                        Jelajahi Sekolah
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="{{ route('public.ppdb') }}"
                       class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold border border-white/30 text-white hover:bg-white/10 transition-colors">
                        Info PPDB
                    </a>
                </div>
            </div>

            {{-- Medallion --}}
            <div class="relative h-full min-h-[260px] hidden md:flex items-center justify-center p-10">
                <div class="relative w-56 h-56 flex items-center justify-center">
                    <div class="spin-slow absolute inset-0 rounded-full border-2 border-dashed" style="border-color: rgba(244,211,94,0.45);" aria-hidden="true"></div>
                    <div class="absolute inset-5 rounded-full border" style="border-color: rgba(244,211,94,0.25);" aria-hidden="true"></div>
                    <div class="relative w-32 h-32 rounded-full flex items-center justify-center font-display text-5xl font-extrabold shadow-lg"
                         style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">
                        Dh
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @if ($settings->established_year || $settings->students_count)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft card-lift">
                <p class="font-display text-4xl font-extrabold text-gradient-gold">{{ \App\Models\School::active()->count() }}</p>
                <p class="text-xs text-ink-500 mt-2 uppercase tracking-wide">Sekolah Binaan</p>
            </div>
            @if ($settings->students_count)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft card-lift">
                <p class="font-display text-4xl font-extrabold text-gradient-gold">{{ number_format($settings->students_count, 0, ',', '.') }}+</p>
                <p class="text-xs text-ink-500 mt-2 uppercase tracking-wide">Siswa Aktif</p>
            </div>
            @endif
            @if ($settings->established_year)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft card-lift">
                <p class="font-display text-4xl font-extrabold text-gradient-gold">{{ $settings->established_year }}</p>
                <p class="text-xs text-ink-500 mt-2 uppercase tracking-wide">Berdiri Sejak</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Sekolah --}}
    @if ($schools->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="eyebrow text-brand-700">Jenjang Pendidikan</p>
                <h2 class="font-display text-3xl font-bold text-ink-900 mt-3">Sekolah Binaan</h2>
                <p class="text-ink-500 text-sm mt-1">Pendidikan Islam berkualitas di setiap jenjang.</p>
            </div>
            <a href="{{ route('public.schools.index') }}" class="hidden sm:inline-flex text-sm font-semibold hover:underline shrink-0" style="color: var(--brand-700);">Lihat semua &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($schools as $school)
                <x-public.school-card :school="$school" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- PPDB + Berita --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <a href="{{ route('public.ppdb') }}"
           class="relative lg:col-span-1 rounded-[2rem] p-8 text-white shadow-soft card-lift flex flex-col justify-between min-h-[220px] overflow-hidden"
           style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="relative">
                <p class="eyebrow" style="color: var(--color-gold-500);">2026 / 2027</p>
                <h3 class="font-display text-2xl font-bold mt-3">PPDB Dibuka</h3>
                <p class="text-white/80 text-sm mt-2">Daftarkan putra-putri Anda di sekolah binaan kami.</p>
            </div>
            <span class="relative text-sm font-bold mt-4" style="color: var(--color-gold-500);">Info pendaftaran &rarr;</span>
        </a>

        <div class="lg:col-span-2">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="eyebrow text-brand-700">Kabar Terkini</p>
                    <h2 class="font-display text-3xl font-bold text-ink-900 mt-3">Berita Terbaru</h2>
                </div>
                <a href="{{ route('public.news.index') }}" class="text-sm font-semibold hover:underline shrink-0" style="color: var(--brand-700);">Lihat semua &rarr;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($news as $item)
                    <x-public.news-card :news="$item" />
                @endforeach
            </div>
        </div>
    </section>
</div>
