<div>
    <div class="mb-6">
        <a href="{{ route('admin.schools.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">&larr; Kembali</a>
        <h2 class="text-2xl font-bold text-ink-900 mt-2">{{ $school && $school->exists ? 'Edit Sekolah' : 'Tambah Sekolah' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-2xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Nama Sekolah *</label>
            <input type="text" wire:model="name" class="chip-input">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Jenjang *</label>
                <select wire:model="level" class="chip-input">
                    <option value="TK">TK</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                </select>
                @error('level')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tahun Berdiri</label>
                <input type="number" wire:model="established_year" min="1900" max="{{ date('Y') + 1 }}" class="chip-input">
                @error('established_year')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Deskripsi</label>
            <textarea wire:model="description" rows="4" class="chip-input" placeholder="Deskripsi sekolah..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Alamat</label>
            <input type="text" wire:model="address" class="chip-input">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Telepon</label>
                <input type="text" wire:model="phone" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Email</label>
                <input type="email" wire:model="email" class="chip-input">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">URL Website Sekolah</label>
            <input type="url" wire:model="website_url" class="chip-input" placeholder="https://">
            @error('website_url')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Logo</label>
                <div class="flex items-center gap-3">
                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}" alt="Pratinjau logo" class="w-12 h-12 rounded-lg object-contain bg-ink-50 border border-ink-200">
                    @elseif ($school?->logo_url)
                        <img src="{{ $school->logo_url }}" alt="Logo saat ini" class="w-12 h-12 rounded-lg object-contain bg-ink-50 border border-ink-200">
                    @endif
                    <input type="file" wire:model="logo" accept="image/*" class="text-sm">
                </div>
                @error('logo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Cover</label>
                <input type="file" wire:model="cover_image" accept="image/*" class="text-sm">
                @error('cover_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" wire:model="is_active" id="active" class="rounded">
            <label for="active" class="text-sm text-ink-700">Sekolah aktif (tampil di publik)</label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-ink-100">
            <a href="{{ route('admin.schools.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn-primary">Simpan</button>
        </div>
    </form>
</div>
