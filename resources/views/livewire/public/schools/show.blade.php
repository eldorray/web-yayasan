<div>
    <div class="public-school-cover">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
    </div>

    <div class="public-container-narrow -mt-14 sm:-mt-16 pb-12">
        <div class="flex flex-col sm:flex-row sm:items-end gap-5 sm:gap-6">
            <div class="relative z-20 shrink-0">
                @if ($school->logo_url)
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white shadow-lg flex items-center justify-center overflow-hidden ring-4 ring-white">
                        <img src="{{ $school->logo_url }}" alt="Logo {{ $school->name }}" class="w-full h-full object-contain p-2">
                    </div>
                @else
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl flex items-center justify-center font-display text-3xl font-extrabold shadow-lg ring-4 ring-white"
                         style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">
                        {{ \Illuminate\Support\Str::limit($school->level, 2, '') }}
                    </div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <a href="{{ route('public.schools.index') }}" wire:navigate class="public-back-link">
                    <x-public.icon name="arrow-left" class="w-4 h-4" />
                    Semua Sekolah
                </a>
            </div>
        </div>

        <header class="mt-6">
            <div class="flex flex-wrap items-center gap-3 mb-3">
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-ink-900">{{ $school->name }}</h1>
                <span class="public-badge public-badge-gold">{{ $school->level }}</span>
            </div>

            <div class="space-y-2 text-sm text-ink-600">
                @if ($school->address)
                    <p class="flex items-start gap-2">
                        <x-public.icon name="map-pin" class="w-4 h-4 shrink-0 mt-0.5" />
                        {{ $school->address }}
                    </p>
                @endif
                @if ($school->phone)
                    <p class="flex items-center gap-2">
                        <x-public.icon name="phone" class="w-4 h-4 shrink-0" />
                        {{ $school->phone }}
                    </p>
                @endif
                @if ($school->email)
                    <p class="flex items-center gap-2">
                        <x-public.icon name="mail" class="w-4 h-4 shrink-0" />
                        {{ $school->email }}
                    </p>
                @endif
                @if ($school->established_year)
                    <p class="flex items-center gap-2">
                        <x-public.icon name="calendar" class="w-4 h-4 shrink-0" />
                        Berdiri sejak {{ $school->established_year }}
                    </p>
                @endif
            </div>

            @if ($school->website_url)
                <a href="{{ $school->website_url }}" target="_blank" rel="noopener"
                   class="public-btn public-btn-brand mt-6">
                    Kunjungi Website
                    <x-public.icon name="external-link" class="w-4 h-4" />
                </a>
            @endif
        </header>

        <div class="public-tabs mt-10" role="tablist">
            @foreach (['about' => 'Tentang', 'ppdb' => 'PPDB', 'gallery' => 'Galeri'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')" role="tab"
                        @class(['public-tab', 'is-active' => $tab === $key])>
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="mt-8">
            @if ($tab === 'about')
                <div class="public-card p-6 sm:p-8">
                    <div class="prose prose-ink max-w-none text-ink-700">
                        {!! $school->description ?? '<p class="text-ink-600">Deskripsi belum tersedia.</p>' !!}
                    </div>
                </div>
            @elseif ($tab === 'ppdb')
                @if ($this->ppdb)
                    <div class="public-card p-6 sm:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                            <h3 class="font-display text-xl font-bold text-ink-900">PPDB {{ $this->ppdb->academic_year }}</h3>
                            <span @class(['public-badge', $this->ppdb->is_open ? 'public-badge-open' : 'public-badge-closed'])>
                                @if ($this->ppdb->is_open)
                                    <x-public.icon name="check-circle" class="w-3.5 h-3.5" /> Dibuka
                                @else
                                    <x-public.icon name="x-circle" class="w-3.5 h-3.5" /> Ditutup
                                @endif
                            </span>
                        </div>
                        @if ($this->ppdb->open_date || $this->ppdb->close_date)
                            <p class="flex items-center gap-2 text-sm text-ink-600 mb-4">
                                <x-public.icon name="calendar" class="w-4 h-4 shrink-0" />
                                {{ $this->ppdb->open_date?->format('d M Y') }} &ndash; {{ $this->ppdb->close_date?->format('d M Y') }}
                            </p>
                        @endif
                        @if ($this->ppdb->requirements)
                            <div class="text-sm text-ink-700 mb-4 leading-relaxed">{!! $this->ppdb->requirements !!}</div>
                        @endif
                        @if ($this->ppdb->fees)
                            <p class="text-sm text-ink-700 mb-5">Biaya: <strong>{{ $this->ppdb->fees }}</strong></p>
                        @endif
                        @if ($this->ppdb->registrationLink())
                            <a href="{{ $this->ppdb->registrationLink() }}" target="_blank" rel="noopener" class="public-btn public-btn-brand">
                                Daftar di website sekolah
                                <x-public.icon name="external-link" class="w-4 h-4" />
                            </a>
                        @endif
                    </div>
                @else
                    <div class="public-empty public-card">
                        <p>Info PPDB belum tersedia untuk sekolah ini.</p>
                    </div>
                @endif
            @elseif ($tab === 'gallery')
                @if ($this->gallery->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($this->gallery as $image)
                            <div class="aspect-square rounded-2xl overflow-hidden public-card">
                                <img src="{{ $image->image_url }}" alt="{{ $image->title ?? '' }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="public-empty public-card">
                        <x-public.icon name="photo" class="w-10 h-10 mx-auto mb-3 text-ink-400" />
                        <p>Belum ada foto untuk sekolah ini.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
