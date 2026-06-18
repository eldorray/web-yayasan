<div>
    <section class="relative text-white py-16 overflow-hidden" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="eyebrow rise rise-1" style="color: var(--color-gold-500);">Kabar Terkini</p>
            <h1 class="font-display text-4xl font-extrabold mt-3 rise rise-2">Berita &amp; Kegiatan</h1>
            <p class="text-white/85 mt-2 rise rise-3">Kabar terbaru dari yayasan &amp; sekolah.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Category filter --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach ($this->categories as $cat)
                @php $active = ($cat === 'all') ? is_null($this->category) : ($this->category === $cat); @endphp
                <button type="button" wire:click="setCategory('{{ $cat }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold capitalize transition-colors"
                        style="{{ $active ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    {{ $cat === 'all' ? 'Semua' : $cat }}
                </button>
            @endforeach
        </div>

        {{-- Featured --}}
        @if ($featured && ! $this->category)
            <div class="mb-10">
                <x-public.news-card :news="$featured" />
            </div>
        @endif

        {{-- Grid --}}
        @if ($news->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($news as $item)
                    @if (! ($featured && $item->id === $featured->id && ! $this->category))
                        <x-public.news-card :news="$item" />
                    @endif
                @endforeach
            </div>
            <div class="mt-10">
                {{ $news->links() }}
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada berita pada kategori ini.</p>
        @endif
    </div>
</div>
