<div x-data="{ lightbox: null, open(url, caption) { this.lightbox = { url, caption }; document.body.style.overflow = 'hidden'; }, close() { this.lightbox = null; document.body.style.overflow = ''; } }" @keydown.escape.window="close()">
    <section class="relative text-white py-16 overflow-hidden" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="eyebrow rise rise-1" style="color: var(--color-gold-500);">Dokumentasi</p>
            <h1 class="font-display text-4xl font-extrabold mt-3 rise rise-2">Galeri Foto</h1>
            <p class="text-white/85 mt-2 rise rise-3">Momen kegiatan yayasan &amp; sekolah.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- School filter --}}
        @if ($this->schoolsWithImages->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <button type="button" wire:click="setSchool(null)"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                        style="{{ is_null($schoolId) ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    Semua
                </button>
                @foreach ($this->schoolsWithImages as $school)
                    <button type="button" wire:click="setSchool({{ $school->id }})"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                            style="{{ $schoolId === $school->id ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                        {{ $school->name }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Masonry-style grid (columns) --}}
        @if ($this->images->isNotEmpty())
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 [&>*]:mb-4">
                @foreach ($this->images as $image)
                    <button type="button" @click="open('{{ $image->image_url }}', '{{ addslashes($image->caption ?? $image->title ?? '') }}')"
                            class="block w-full break-inside-avoid rounded-xl overflow-hidden shadow-soft hover:opacity-90 transition-opacity">
                        <img src="{{ $image->image_url }}" alt="{{ $image->title ?? '' }}" class="w-full h-auto">
                    </button>
                @endforeach
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada foto di galeri.</p>
        @endif
    </div>

    {{-- Lightbox --}}
    <template x-if="lightbox">
        <div class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click="close()">
            <div class="max-w-4xl max-h-full relative" @click.stop>
                <img :src="lightbox?.url" :alt="lightbox?.caption" class="max-w-full max-h-[80vh] rounded-xl">
                <p x-text="lightbox?.caption" class="text-white/90 text-center mt-3 text-sm" x-show="lightbox?.caption"></p>
                <button type="button" @click="close()" class="absolute top-4 right-4 text-white text-3xl leading-none">&times;</button>
            </div>
        </div>
    </template>
</div>
