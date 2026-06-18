<div>
    {{-- Hero split --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center rounded-3xl overflow-hidden shadow-soft"
             style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div class="p-8 md:p-12 text-white">
                <p class="text-sm font-semibold tracking-wider uppercase mb-3" style="color: var(--color-gold-500);">Yayasan Pendidikan</p>
                <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $settings->name }}</h1>
                <p class="mt-4 text-white/85">{{ $settings->tagline }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('public.schools.index') }}" class="inline-flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-semibold transition-colors" style="background-color: var(--color-gold-500); color: var(--brand-900);">
                        Lihat Sekolah &rarr;
                    </a>
                    <a href="{{ route('public.about') }}" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold border border-white/30 text-white hover:bg-white/10">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="h-full min-h-[240px] flex items-center justify-center p-8" style="background: linear-gradient(160deg, var(--brand-600), var(--brand-800));">
                <div class="w-32 h-32 rounded-full border-4 flex items-center justify-center text-5xl font-bold" style="border-color: var(--color-gold-500); color: var(--color-gold-500);">Dh</div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @if ($settings->established_year || $settings->students_count)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ \App\Models\School::active()->count() }}</p>
                <p class="text-xs text-ink-500 mt-1">Sekolah Binaan</p>
            </div>
            @if ($settings->students_count)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ number_format($settings->students_count, 0, ',', '.') }}+</p>
                <p class="text-xs text-ink-500 mt-1">Siswa</p>
            </div>
            @endif
            @if ($settings->established_year)
            <div class="bg-white rounded-2xl p-6 text-center shadow-soft">
                <p class="text-3xl font-bold" style="color: var(--brand-700);">{{ $settings->established_year }}</p>
                <p class="text-xs text-ink-500 mt-1">Berdiri Sejak</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Sekolah --}}
    @if ($schools->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-ink-900">Sekolah Binaan</h2>
                <p class="text-ink-500 text-sm">Pendidikan Islam berkualitas di setiap jenjang.</p>
            </div>
            <a href="{{ route('public.schools.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Lihat semua &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($schools as $school)
                <x-public.school-card :school="$school" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- PPDB + Berita grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <a href="{{ route('public.ppdb') }}" class="lg:col-span-1 rounded-2xl p-8 text-white shadow-soft hover:shadow-md transition-shadow flex flex-col justify-between min-h-[200px]" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase mb-2" style="color: var(--color-gold-500);">Tahun Ajaran 2026/2027</p>
                <h3 class="text-xl font-bold">PPDB Dibuka</h3>
            </div>
            <span class="text-sm font-semibold mt-4" style="color: var(--color-gold-500);">Info pendaftaran &rarr;</span>
        </a>

        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-ink-900">Berita Terbaru</h2>
                <a href="{{ route('public.news.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Lihat semua &rarr;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($news as $item)
                    <x-public.news-card :news="$item" />
                @endforeach
            </div>
        </div>
    </section>
</div>
