<div>
    <div class="mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">&larr; Kembali</a>
        <h2 class="text-2xl font-bold text-ink-900 mt-2">{{ $news && $news->exists ? 'Edit Berita' : 'Tambah Berita' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-3xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Judul *</label>
            <input type="text" wire:model="title" class="chip-input">
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Kategori</label>
                <select wire:model="category" class="chip-input">
                    <option value="yayasan">Yayasan</option>
                    <option value="sekolah">Sekolah</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Sekolah (opsional)</label>
                <select wire:model="school_id" class="chip-input">
                    <option value="">&mdash; Tidak terikat sekolah &mdash;</option>
                    @foreach ($this->schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Ringkasan</label>
            <textarea wire:model="excerpt" rows="2" class="chip-input"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Isi Berita</label>
            <x-trix-editor model="body" id="body" placeholder="Tulis isi berita..." />
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Cover</label>
            <input type="file" wire:model="cover_image" accept="image/*" class="text-sm">
            @error('cover_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tanggal Terbit</label>
                <input type="datetime-local" wire:model="published_at" class="chip-input">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm text-ink-700">
                    <input type="checkbox" wire:model="is_published" class="rounded"> Terbitkan
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-ink-100">
            <a href="{{ route('admin.news.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
