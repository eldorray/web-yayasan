<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-ink-900 mb-1">Dashboard</h2>
            <p class="text-ink-500 text-sm">Ringkasan konten Yayasan Daarul Hikmah Al Madani.</p>
        </div>
        <span class="text-sm text-ink-500">Selamat datang, {{ auth()->user()->name }}</span>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Sekolah</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['schools'] }}</p>
            <p class="text-xs text-ink-500 mt-1">{{ $this->stats['active_schools'] }} aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Berita</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['news'] }}</p>
            <p class="text-xs text-ink-500 mt-1">{{ $this->stats['published_news'] }} terbit</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-soft">
            <p class="text-xs text-ink-500 uppercase tracking-wide">Galeri</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--brand-700);">{{ $this->stats['gallery'] }}</p>
            <p class="text-xs text-ink-500 mt-1">foto</p>
        </div>
        <div class="rounded-2xl p-6 text-white" style="background: linear-gradient(135deg, var(--brand-700), var(--brand-900));">
            <p class="text-xs uppercase tracking-wide" style="color: var(--color-gold-500);">Yayasan</p>
            <p class="text-sm font-bold mt-2 leading-tight">{{ $this->settings->name }}</p>
        </div>
    </div>

    {{-- Recent news --}}
    <div class="bg-white rounded-2xl p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-ink-900">Berita Terbaru</h3>
            <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold hover:underline" style="color: var(--brand-700);">Kelola &rarr;</a>
        </div>
        @if ($this->recentNews->isNotEmpty())
            <ul class="divide-y divide-ink-100">
                @foreach ($this->recentNews as $news)
                    <li class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-ink-800">{{ $news->title }}</p>
                            <p class="text-xs text-ink-500">{{ $news->published_at?->format('d M Y') ?? 'Draft' }} &middot; {{ $news->school?->name ?? 'Yayasan' }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $news->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-ink-50 text-ink-500' }}">
                            {{ $news->is_published ? 'Terbit' : 'Draft' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-ink-500 text-sm">Belum ada berita.</p>
        @endif
    </div>
</div>
