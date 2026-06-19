<div>
    <x-public.page-hero
        eyebrow="Penerimaan Peserta Didik Baru"
        title="PPDB Yayasan Daarul Hikmah Al Madani"
        description="Pendaftaran dilakukan di website masing-masing sekolah. Pilih sekolah tujuan di bawah."
        centered
    />

    <div class="public-container-narrow public-section space-y-12">
        @forelse ($this->ppdbInfos as $year => $infos)
            <section>
                <x-public.section-header
                    eyebrow="Tahun Ajaran"
                    :title="$year"
                />
                <div class="grid gap-4">
                    @foreach ($infos as $info)
                        <div class="public-ppdb-card">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-display font-bold text-ink-900">{{ $info->school->name }}</h3>
                                    <span class="public-badge public-badge-gold">{{ $info->school->level }}</span>
                                </div>
                                @if ($info->open_date || $info->close_date)
                                    <p class="flex items-center gap-2 text-sm text-ink-600">
                                        <x-public.icon name="calendar" class="w-4 h-4 shrink-0" />
                                        {{ $info->open_date?->format('d M Y') }} &ndash; {{ $info->close_date?->format('d M Y') }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <span @class(['public-badge', $info->is_open ? 'public-badge-open' : 'public-badge-closed'])>
                                    {{ $info->is_open ? 'Dibuka' : 'Ditutup' }}
                                </span>
                                <a href="{{ route('public.schools.show', $info->school->slug) }}" wire:navigate class="public-link motion-tap">
                                    Detail sekolah
                                </a>
                                @if ($info->registration_url && $info->is_open)
                                    <a href="{{ $info->registration_url }}" target="_blank" rel="noopener" class="public-btn public-btn-brand">
                                        Daftar
                                        <x-public.icon name="external-link" class="w-4 h-4" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="public-empty public-card">
                <x-public.icon name="school" class="w-10 h-10 mx-auto mb-3 text-ink-400" />
                <p>Info PPDB belum tersedia. Silakan hubungi yayasan.</p>
            </div>
        @endforelse
    </div>

    <x-public.cta-banner title="Butuh bantuan?" description="Hubungi kami untuk informasi lebih lanjut tentang pendaftaran.">
        @if ($settings->phone)
            <a href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}" class="public-btn public-btn-outline motion-tap">
                <x-public.icon name="phone" class="w-4 h-4" />
                {{ $settings->phone }}
            </a>
        @endif
        @if ($settings->email)
            <a href="mailto:{{ $settings->email }}" class="public-btn public-btn-outline motion-tap">
                <x-public.icon name="mail" class="w-4 h-4" />
                {{ $settings->email }}
            </a>
        @endif
    </x-public.cta-banner>
</div>
