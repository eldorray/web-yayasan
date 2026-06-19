<div>
    {{-- Hero — Hook + dual CTA --}}
    <section class="public-container pt-6 sm:pt-8 pb-4">
        <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-0 items-stretch rounded-[2rem] overflow-hidden shadow-soft min-h-[420px]"
             style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <div class="pattern-stars absolute inset-0 opacity-70" aria-hidden="true"></div>
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full blur-3xl opacity-25" style="background: var(--color-gold-500);" aria-hidden="true"></div>

            <div class="relative p-8 sm:p-12 lg:p-14 text-white flex flex-col justify-center">
                <p class="eyebrow rise rise-1" style="color: var(--color-gold-500);">Yayasan Pendidikan Islam</p>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.08] mt-4 rise rise-2">
                    {{ $settings->name }}
                </h1>
                <p class="mt-5 text-white/90 text-base sm:text-lg max-w-lg leading-relaxed rise rise-3">{{ $settings->tagline }}</p>
                <div class="mt-8 flex flex-wrap gap-3 rise rise-4">
                    <a href="{{ route('public.schools.index') }}" wire:navigate class="public-btn public-btn-gold">
                        Jelajahi Sekolah
                        <x-public.icon name="arrow-right" class="w-4 h-4" />
                    </a>
                    <a href="{{ route('public.ppdb') }}" wire:navigate class="public-btn public-btn-outline">
                        Info PPDB
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:flex items-center justify-center p-10">
                <div class="relative w-56 h-56 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border-2 border-dashed opacity-60" style="border-color: rgba(244,211,94,0.45);" aria-hidden="true"></div>
                    <div class="absolute inset-5 rounded-full border opacity-40" style="border-color: rgba(244,211,94,0.25);" aria-hidden="true"></div>
                    @if ($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo {{ $settings->name }}" class="relative w-28 h-28 object-contain rounded-2xl bg-white/95 p-2 shadow-lg">
                    @else
                        <div class="relative w-28 h-28 rounded-full flex items-center justify-center font-display text-5xl font-extrabold shadow-lg"
                             style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">
                            Dh
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Social proof stats --}}
    @if ($settings->established_year || $settings->students_count)
    <section class="public-container pb-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-public.stat-card :value="\App\Models\School::active()->count()" label="Sekolah Binaan" icon="school" />
            @if ($settings->students_count)
                <x-public.stat-card :value="number_format($settings->students_count, 0, ',', '.').'+'" label="Siswa Aktif" icon="users" />
            @endif
            @if ($settings->established_year)
                <x-public.stat-card :value="$settings->established_year" label="Berdiri Sejak" icon="calendar" />
            @endif
        </div>
    </section>
    @endif

    {{-- Sekolah binaan --}}
    @if ($schools->isNotEmpty())
    <section class="public-container public-section">
        <x-public.section-header
            eyebrow="Jenjang Pendidikan"
            title="Sekolah Binaan"
            description="Pendidikan Islam berkualitas di setiap jenjang."
            :href="route('public.schools.index')"
            link-label="Lihat semua sekolah"
        />
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($schools as $school)
                <x-public.school-card :school="$school" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- Visi teaser (trust / social proof) --}}
    @if ($settings->vision)
    <section class="public-container pb-4">
        <div class="public-card p-8 sm:p-10 pattern-stars-ink">
            <div class="max-w-3xl">
                <p class="eyebrow text-brand-700">Visi Kami</p>
                <blockquote class="font-display text-xl sm:text-2xl font-semibold text-ink-900 mt-4 leading-relaxed">
                    "{{ $settings->vision }}"
                </blockquote>
                <a href="{{ route('public.about') }}" wire:navigate class="public-link motion-tap mt-6">
                    Pelajari tentang yayasan
                    <x-public.icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Berita + PPDB --}}
    <section class="public-container public-section">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <a href="{{ route('public.ppdb') }}" wire:navigate
               class="motion-tap cursor-pointer relative lg:col-span-1 rounded-[2rem] p-8 text-white shadow-soft card-lift flex flex-col justify-between min-h-[240px] overflow-hidden"
               style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
                <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
                <div class="relative">
                    <p class="eyebrow" style="color: var(--color-gold-500);">2026 / 2027</p>
                    <h3 class="font-display text-2xl font-bold mt-3">PPDB Dibuka</h3>
                    <p class="text-white/90 text-sm mt-2 leading-relaxed">Daftarkan putra-putri Anda di sekolah binaan kami.</p>
                </div>
                <span class="relative public-link mt-6" style="color: var(--color-gold-500);">
                    Info pendaftaran
                    <x-public.icon name="arrow-right" class="w-4 h-4" />
                </span>
            </a>

            <div class="lg:col-span-2">
                <x-public.section-header
                    eyebrow="Kabar Terkini"
                    title="Berita Terbaru"
                    :href="route('public.news.index')"
                    link-label="Semua berita"
                />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach ($news as $item)
                        <x-public.news-card :news="$item" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <x-public.cta-banner
        title="Siap bergabung dengan keluarga besar kami?"
        description="Hubungi yayasan atau jelajahi profil sekolah untuk informasi lengkap."
    >
        <a href="{{ route('public.schools.index') }}" wire:navigate class="public-btn public-btn-gold">Lihat Sekolah</a>
        <a href="{{ route('public.about') }}" wire:navigate class="public-btn public-btn-outline">Tentang Kami</a>
    </x-public.cta-banner>
</div>
