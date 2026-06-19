<div>
    <x-public.page-hero
        eyebrow="Kabar Terkini"
        title="Berita & Kegiatan"
        description="Kabar terbaru dari yayasan & sekolah."
    />

    <div class="public-container public-section">
        <div class="flex flex-wrap gap-2 mb-8" role="group" aria-label="Filter kategori">
            @foreach ($this->categories as $cat)
                @php $active = ($cat === 'all') ? is_null($this->category) : ($this->category === $cat); @endphp
                <x-public.filter-chip wire:click="setCategory('{{ $cat }}')" :active="$active" class="capitalize">
                    {{ $cat === 'all' ? 'Semua' : $cat }}
                </x-public.filter-chip>
            @endforeach
        </div>

        @if ($featured && ! $this->category)
            <div class="mb-10">
                <x-public.news-card :news="$featured" :featured="true" />
            </div>
        @endif

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
            <div class="public-empty public-card">
                <x-public.icon name="newspaper" class="w-10 h-10 mx-auto mb-3 text-ink-400" />
                <p>Belum ada berita pada kategori ini.</p>
            </div>
        @endif
    </div>
</div>
