<div>
    {{-- Hero --}}
    <section class="py-16 text-white text-center" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-semibold tracking-wider uppercase mb-3" style="color: var(--color-gold-500);">Penerimaan Peserta Didik Baru</p>
            <h1 class="text-3xl md:text-4xl font-bold">PPDB Yayasan Daarul Hikmah Al Madani</h1>
            <p class="mt-4 text-white/85">Pendaftaran dilakukan di website masing-masing sekolah. Pilih sekolah tujuan di bawah.</p>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        @forelse ($this->ppdbInfos as $year => $infos)
            <section>
                <h2 class="text-2xl font-bold text-ink-900 mb-6">Tahun Ajaran {{ $year }}</h2>
                <div class="grid gap-4">
                    @foreach ($infos as $info)
                        <div class="bg-white rounded-2xl p-6 shadow-soft flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-ink-900">{{ $info->school->name }}</h3>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $info->school->level }}</span>
                                </div>
                                <p class="text-sm text-ink-500 mt-1">
                                    @if ($info->open_date || $info->close_date)
                                        {{ $info->open_date?->format('d M Y') }} &ndash; {{ $info->close_date?->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $info->is_open ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $info->is_open ? 'Dibuka' : 'Ditutup' }}
                                </span>
                                <a href="{{ route('public.schools.show', $info->school->slug) }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Detail sekolah</a>
                                @if ($info->registration_url && $info->is_open)
                                    <a href="{{ $info->registration_url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-white" style="background-color: var(--brand-700);">Daftar &rarr;</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="text-center text-ink-500 py-12">Info PPDB belum tersedia. Silakan hubungi yayasan.</p>
        @endforelse

        {{-- Kontak --}}
        <div class="rounded-2xl p-8 text-center text-white" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
            <h3 class="text-xl font-bold mb-2">Butuh bantuan?</h3>
            <p class="text-white/85 mb-4">Hubungi kami untuk informasi lebih lanjut.</p>
            @if ($settings->phone || $settings->email)
                <div class="flex justify-center gap-6 text-sm">
                    @if ($settings->phone)<span>{{ $settings->phone }}</span>@endif
                    @if ($settings->email)<span>{{ $settings->email }}</span>@endif
                </div>
            @endif
        </div>
    </div>
</div>
