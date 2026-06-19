<div x-data="{ lightbox: null, open(url, caption) { this.lightbox = { url, caption }; document.body.style.overflow = 'hidden'; }, close() { this.lightbox = null; document.body.style.overflow = ''; } }" @keydown.escape.window="close()">
    <x-public.page-hero
        eyebrow="Dokumentasi"
        title="Galeri Foto"
        description="Momen kegiatan yayasan & sekolah."
    />

    <div class="public-container public-section">
        @if ($this->schoolsWithImages->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8" role="group" aria-label="Filter sekolah">
                <x-public.filter-chip wire:click="setSchool(null)" :active="is_null($schoolId)">
                    Semua
                </x-public.filter-chip>
                @foreach ($this->schoolsWithImages as $school)
                    <x-public.filter-chip wire:click="setSchool({{ $school->id }})" :active="$schoolId === $school->id">
                        {{ $school->name }}
                    </x-public.filter-chip>
                @endforeach
            </div>
        @endif

        @if ($this->images->isNotEmpty())
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 [&>*]:mb-4">
                @foreach ($this->images as $image)
                    <button type="button"
                            @click="open('{{ $image->image_url }}', '{{ addslashes($image->caption ?? $image->title ?? '') }}')"
                            class="motion-tap cursor-pointer block w-full break-inside-avoid rounded-2xl overflow-hidden public-card hover:shadow-lg transition-shadow duration-200">
                        <img src="{{ $image->image_url }}" alt="{{ $image->title ?? 'Foto galeri' }}" class="w-full h-auto">
                    </button>
                @endforeach
            </div>
        @else
            <div class="public-empty public-card">
                <x-public.icon name="photo" class="w-10 h-10 mx-auto mb-3 text-ink-400" />
                <p>Belum ada foto di galeri.</p>
            </div>
        @endif
    </div>

    <template x-if="lightbox">
        <div class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="close()" role="dialog" aria-modal="true">
            <div class="max-w-4xl max-h-full relative"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>
                <img :src="lightbox?.url" :alt="lightbox?.caption" class="max-w-full max-h-[80vh] rounded-2xl">
                <p x-text="lightbox?.caption" class="text-white/90 text-center mt-3 text-sm" x-show="lightbox?.caption"></p>
                <button type="button" @click="close()" class="motion-tap cursor-pointer absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 text-white text-2xl leading-none hover:bg-white/20 transition-colors" aria-label="Tutup">&times;</button>
            </div>
        </div>
    </template>
</div>
