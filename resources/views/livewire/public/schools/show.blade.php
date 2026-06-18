<div>
    {{-- Cover --}}
    <div class="h-48 relative" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-600));">
        <div class="absolute -bottom-8 left-6 sm:left-10">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-soft" style="background-color: var(--color-gold-500); color: var(--brand-900);">
                {{ \Illuminate\Support\Str::limit($school->level, 2, '') }}
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-12">
        {{-- Breadcrumb --}}
        <a href="{{ route('public.schools.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">&larr; Semua Sekolah</a>

        {{-- Header --}}
        <div class="mt-4">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-ink-900">{{ $school->name }}</h1>
                <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $school->level }}</span>
            </div>
            <div class="text-sm text-ink-500 space-y-1">
                @if ($school->address)<p>{{ $school->address }}</p>@endif
                @if ($school->phone || $school->email)
                    <p>@if ($school->phone){{ $school->phone }}@endif @if ($school->phone && $school->email)&middot; @endif @if ($school->email){{ $school->email }}@endif</p>
                @endif
                @if ($school->established_year)<p>Berdiri sejak {{ $school->established_year }}</p>@endif
            </div>
            @if ($school->website_url)
                <a href="{{ $school->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded-full px-5 py-2.5 text-sm font-semibold mt-4 text-white" style="background-color: var(--brand-700);">
                    Kunjungi Website &rarr;
                </a>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="flex gap-6 border-b border-ink-200 mt-8">
            @foreach (['about' => 'Tentang', 'ppdb' => 'PPDB', 'gallery' => 'Galeri'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')"
                        class="pb-3 text-sm font-semibold border-b-2 transition-colors {{ $tab === $key ? '' : 'text-ink-500 border-transparent hover:text-ink-800' }}"
                        style="{{ $tab === $key ? 'color: var(--brand-700); border-color: var(--brand-700);' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Tab content --}}
        <div class="mt-8">
            @if ($tab === 'about')
                <div class="prose prose-ink max-w-none">
                    {!! $school->description ?? '<p class="text-ink-500">Deskripsi belum tersedia.</p>' !!}
                </div>
            @elseif ($tab === 'ppdb')
                @if ($this->ppdb)
                    <div class="bg-white rounded-2xl p-6 shadow-soft">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-ink-900">PPDB {{ $this->ppdb->academic_year }}</h3>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $this->ppdb->is_open ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                {{ $this->ppdb->is_open ? 'Dibuka' : 'Ditutup' }}
                            </span>
                        </div>
                        @if ($this->ppdb->open_date || $this->ppdb->close_date)
                            <p class="text-sm text-ink-600 mb-3">{{ $this->ppdb->open_date?->format('d M Y') }} &ndash; {{ $this->ppdb->close_date?->format('d M Y') }}</p>
                        @endif
                        @if ($this->ppdb->requirements)<div class="text-sm text-ink-700 mb-3">{!! $this->ppdb->requirements !!}</div>@endif
                        @if ($this->ppdb->fees)<p class="text-sm text-ink-700 mb-4">Biaya: <strong>{{ $this->ppdb->fees }}</strong></p>@endif
                        @if ($this->ppdb->registration_url)
                            <a href="{{ $this->ppdb->registration_url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white" style="background-color: var(--brand-700);">
                                Daftar di website sekolah &rarr;
                            </a>
                        @endif
                    </div>
                @else
                    <p class="text-ink-500">Info PPDB belum tersedia untuk sekolah ini.</p>
                @endif
            @elseif ($tab === 'gallery')
                @if ($this->gallery->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($this->gallery as $image)
                            <div class="aspect-square rounded-xl overflow-hidden shadow-soft">
                                <img src="{{ $image->image_url }}" alt="{{ $image->title ?? '' }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-ink-500">Belum ada foto untuk sekolah ini.</p>
                @endif
            @endif
        </div>
    </div>
</div>
