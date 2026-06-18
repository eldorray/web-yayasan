@php
    $navItems = [
        ['route' => 'public.home', 'label' => 'Beranda'],
        ['route' => 'public.about', 'label' => 'Tentang'],
        ['route' => 'public.schools.index', 'label' => 'Sekolah'],
        ['route' => 'public.news.index', 'label' => 'Berita'],
        ['route' => 'public.gallery.index', 'label' => 'Galeri'],
    ];
    $settings = \App\Models\SiteSetting::current();
@endphp

<header class="sticky top-0 z-40 backdrop-blur-md bg-white/80 border-b border-ink-100"
    x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('public.home') }}" class="flex items-center gap-2.5 group">
                <span class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-extrabold text-base leading-none shadow-sm transition-transform group-hover:scale-105"
                      style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                <span class="font-display font-bold text-ink-900 tracking-tight hidden sm:block">{{ \Illuminate\Support\Str::limit($settings->name, 28) }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                @foreach ($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="nav-underline px-3 py-2 rounded-full text-sm font-medium transition-colors {{ $isActive ? 'is-active text-brand-800' : 'text-ink-600 hover:text-ink-900' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('public.ppdb') }}"
                   class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold text-white transition-colors"
                   style="background-color: var(--brand-700);">
                    PPDB
                </a>

                <button type="button" @click="open = ! open"
                    class="md:hidden w-9 h-9 rounded-full flex items-center justify-center text-ink-600 hover:bg-ink-50"
                    aria-label="Menu">
                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak class="md:hidden pb-4 flex flex-col gap-1">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="px-3 py-2 rounded-xl text-sm font-medium transition-colors
                          {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')
                              ? 'text-brand-800 bg-brand-50'
                              : 'text-ink-600 hover:text-ink-900 hover:bg-ink-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</header>
