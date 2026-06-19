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

<div class="public-nav-shell" data-motion-header x-data="{ open: false }">
    <header class="public-nav-bar">
        <div class="px-4 sm:px-6">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <a href="{{ route('public.home') }}" wire:navigate class="flex items-center gap-2.5 group motion-tap cursor-pointer">
                    @if ($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo {{ $settings->name }}"
                             class="w-9 h-9 rounded-xl object-contain bg-white shadow-sm">
                    @else
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-extrabold text-base leading-none shadow-sm"
                              style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                    @endif
                    <span class="font-display font-bold text-ink-900 tracking-tight hidden sm:block">{{ \Illuminate\Support\Str::limit($settings->name, 28) }}</span>
                </a>

                <nav class="hidden md:flex items-center gap-1" aria-label="Navigasi utama">
                    @foreach ($navItems as $item)
                        @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'); @endphp
                        <a href="{{ route($item['route']) }}" wire:navigate
                           class="nav-underline motion-tap cursor-pointer px-3 py-2 rounded-full text-sm font-medium transition-colors duration-200 {{ $isActive ? 'is-active text-brand-800' : 'text-ink-600 hover:text-ink-900' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('public.ppdb') }}" wire:navigate class="public-btn public-btn-brand hidden sm:inline-flex">
                        PPDB
                    </a>

                    <button type="button" @click="open = ! open"
                        class="motion-tap cursor-pointer md:hidden w-9 h-9 rounded-full flex items-center justify-center text-ink-600 hover:bg-ink-50"
                        aria-label="Buka menu" :aria-expanded="open">
                        <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <line x1="3" x2="21" y1="6" y2="6" />
                            <line x1="3" x2="21" y1="12" y2="12" />
                            <line x1="3" x2="21" y1="18" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden pb-4 flex flex-col gap-1 border-t border-ink-100 pt-3">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" wire:navigate @click="open = false"
                       class="motion-tap cursor-pointer px-3 py-2.5 rounded-xl text-sm font-medium transition-colors duration-200
                              {{ request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')
                                  ? 'text-brand-800 bg-brand-50'
                                  : 'text-ink-600 hover:text-ink-900 hover:bg-ink-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('public.ppdb') }}" wire:navigate @click="open = false"
                   class="public-btn public-btn-brand mt-2 sm:hidden">
                    PPDB
                </a>
            </div>
        </div>
    </header>
</div>
