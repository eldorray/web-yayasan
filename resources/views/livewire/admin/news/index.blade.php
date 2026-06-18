<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-ink-900">Kelola Berita</h2>
            <p class="text-ink-500 text-sm">{{ $this->news->total() }} berita</p>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-primary">+ Tambah Berita</a>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berita..." class="chip-input mb-6 max-w-sm">

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <table class="w-full text-left">
            <thead class="text-xs text-ink-400 border-b border-ink-100 uppercase">
                <tr>
                    <th class="font-medium px-4 py-3">Judul</th>
                    <th class="font-medium px-4 py-3">Kategori</th>
                    <th class="font-medium px-4 py-3">Status</th>
                    <th class="font-medium px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-ink-800">
                @forelse ($this->news as $item)
                    <tr class="border-b border-ink-50 last:border-0">
                        <td class="px-4 py-3 font-medium">{{ $item->title }}</td>
                        <td class="px-4 py-3 capitalize">{{ $item->category }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $item->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-ink-50 text-ink-500' }}">{{ $item->is_published ? 'Terbit' : 'Draft' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.news.edit', $item) }}" class="text-sm font-medium hover:underline" style="color: var(--brand-700);">Edit</a>
                            <button type="button" wire:click="confirmDelete({{ $item->id }})" class="text-sm font-medium text-red-600 hover:underline ml-3">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-ink-500">Belum ada berita.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $this->news->links() }}</div>

    @if ($deleteId)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" wire:click="$set('deleteId', null)">
            <div class="bg-white rounded-2xl p-6 max-w-sm w-full" @click.stop>
                <h3 class="font-bold text-ink-900 mb-2">Hapus berita?</h3>
                <p class="text-sm text-ink-500 mb-6">Tindakan ini tidak bisa dibatalkan.</p>
                <div class="flex gap-3 justify-end">
                    <button type="button" wire:click="$set('deleteId', null)" class="btn-ghost">Batal</button>
                    <button type="button" wire:click="delete" class="inline-flex items-center rounded-full px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
