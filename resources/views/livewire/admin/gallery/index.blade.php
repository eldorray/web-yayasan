<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-ink-900">Kelola Galeri</h2>
        <p class="text-ink-500 text-sm">Upload foto kegiatan yayasan &amp; sekolah.</p>
    </div>

    {{-- Upload form --}}
    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 mb-8 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Pilih Foto *</label>
                <input type="file" wire:model="photos" multiple accept="image/*" class="text-sm">
                @error('photos')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                @error('photos.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Sekolah (opsional)</label>
                <select wire:model="school_id" class="chip-input">
                    <option value="">&mdash; Galeri Yayasan &mdash;</option>
                    @foreach ($this->schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Caption</label>
                <input type="text" wire:model="caption" class="chip-input">
            </div>
        </div>
        <div class="flex justify-end items-center gap-3">
            <span wire:loading class="text-sm text-ink-500">Mengunggah...</span>
            <button type="submit" class="btn-primary">Upload</button>
        </div>
    </form>

    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse ($this->images as $image)
            <div class="bg-white rounded-xl overflow-hidden shadow-soft">
                <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? '' }}" class="w-full h-24 object-cover">
                <div class="p-2">
                    <p class="text-xs text-ink-600 truncate">{{ $image->caption ?? '(tanpa caption)' }}</p>
                    <button type="button" wire:click="confirmDelete({{ $image->id }})" class="text-xs text-red-600 mt-1">Hapus</button>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-ink-500 py-8">Belum ada foto.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $this->images->links() }}</div>

    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="$set('deleteId', null)">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full" @click.stop>
                <h3 class="font-bold text-ink-900 mb-2">Hapus foto?</h3>
                <div class="flex gap-3 justify-end mt-4">
                    <button type="button" wire:click="$set('deleteId', null)" class="btn-ghost">Batal</button>
                    <button type="button" wire:click="delete" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
