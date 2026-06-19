<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-ink-900">Pengaturan Yayasan</h2>
        <p class="text-ink-500 text-sm">Identitas yayasan yang tampil di seluruh halaman publik.</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl shadow-soft p-6 space-y-5 max-w-3xl">
        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Nama Yayasan *</label>
            <input type="text" wire:model="name" class="chip-input">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Tagline</label>
            <input type="text" wire:model="tagline" class="chip-input">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Visi</label>
                <textarea wire:model="vision" rows="3" class="chip-input"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Misi</label>
                <textarea wire:model="mission" rows="3" class="chip-input"></textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Sejarah</label>
            <textarea wire:model="history" rows="5" class="chip-input"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Alamat</label>
                <input type="text" wire:model="address" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Telepon</label>
                <input type="text" wire:model="phone" class="chip-input">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Email</label>
                <input type="email" wire:model="email" class="chip-input">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Tahun Berdiri</label>
                <input type="number" wire:model="established_year" class="chip-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink-700 mb-1">Jumlah Siswa</label>
                <input type="number" wire:model="students_count" class="chip-input">
            </div>
        </div>

        <fieldset class="border border-ink-200 rounded-2xl p-4">
            <legend class="text-sm font-medium text-ink-700 px-2">Media Sosial</legend>
            <div class="space-y-3">
                <input type="url" wire:model="facebook" class="chip-input" placeholder="https://facebook.com/...">
                <input type="url" wire:model="instagram" class="chip-input" placeholder="https://instagram.com/...">
                <input type="url" wire:model="youtube" class="chip-input" placeholder="https://youtube.com/@...">
            </div>
        </fieldset>

        <div>
            <label class="block text-sm font-medium text-ink-700 mb-1">Logo Yayasan</label>
            <div class="flex items-center gap-3">
                @if ($logo)
                    <img src="{{ $logo->temporaryUrl() }}" alt="Pratinjau logo" class="w-12 h-12 rounded-lg object-contain bg-ink-50 border border-ink-200">
                @elseif ($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="Logo saat ini" class="w-12 h-12 rounded-lg object-contain bg-ink-50 border border-ink-200">
                @endif
                <input type="file" wire:model="logo" accept="image/*" class="text-sm">
            </div>
            @error('logo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end pt-4 border-t border-ink-100">
            <button type="submit" class="btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>
