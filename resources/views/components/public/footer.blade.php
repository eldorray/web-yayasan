@php $settings = \App\Models\SiteSetting::current(); @endphp

<footer class="relative mt-16 lg:mt-24 text-white overflow-hidden" style="background-color: var(--brand-900);">
    <div class="pattern-stars absolute inset-0 opacity-50" aria-hidden="true"></div>
    <div class="absolute top-0 inset-x-0 h-0.5" style="background: linear-gradient(90deg, transparent, var(--color-gold-500), transparent);" aria-hidden="true"></div>

    <div class="relative public-container py-12 lg:py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-2.5 mb-4">
                    @if ($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo {{ $settings->name }}" class="w-10 h-10 rounded-xl object-contain bg-white p-0.5">
                    @else
                        <span class="w-10 h-10 rounded-xl flex items-center justify-center font-display font-extrabold"
                              style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                    @endif
                    <span class="font-display font-bold leading-snug">{{ $settings->name }}</span>
                </div>
                <p class="text-sm text-white/85 leading-relaxed max-w-xs">{{ $settings->tagline }}</p>
            </div>

            <div>
                <h4 class="font-semibold mb-4 text-sm" style="color: var(--color-gold-500);">Navigasi</h4>
                <ul class="space-y-2.5 text-sm text-white/85">
                    <li><a href="{{ route('public.about') }}" wire:navigate class="motion-tap cursor-pointer hover:text-white transition-colors duration-200">Tentang Kami</a></li>
                    <li><a href="{{ route('public.schools.index') }}" wire:navigate class="motion-tap cursor-pointer hover:text-white transition-colors duration-200">Sekolah Binaan</a></li>
                    <li><a href="{{ route('public.news.index') }}" wire:navigate class="motion-tap cursor-pointer hover:text-white transition-colors duration-200">Berita</a></li>
                    <li><a href="{{ route('public.gallery.index') }}" wire:navigate class="motion-tap cursor-pointer hover:text-white transition-colors duration-200">Galeri</a></li>
                    <li><a href="{{ route('public.ppdb') }}" wire:navigate class="motion-tap cursor-pointer hover:text-white transition-colors duration-200">PPDB</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4 text-sm" style="color: var(--color-gold-500);">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm text-white/85">
                    @if ($settings->address)
                        <li class="flex items-start gap-2">
                            <x-public.icon name="map-pin" class="w-4 h-4 shrink-0 mt-0.5" />
                            {{ $settings->address }}
                        </li>
                    @endif
                    @if ($settings->phone)
                        <li class="flex items-center gap-2">
                            <x-public.icon name="phone" class="w-4 h-4 shrink-0" />
                            {{ $settings->phone }}
                        </li>
                    @endif
                    @if ($settings->email)
                        <li class="flex items-center gap-2">
                            <x-public.icon name="mail" class="w-4 h-4 shrink-0" />
                            {{ $settings->email }}
                        </li>
                    @endif
                </ul>
            </div>

            @if (! empty($settings->socials))
            <div>
                <h4 class="font-semibold mb-4 text-sm" style="color: var(--color-gold-500);">Media Sosial</h4>
                <div class="flex flex-wrap gap-3">
                    @foreach ($settings->socials as $platform => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                           class="motion-tap cursor-pointer capitalize text-sm text-white/85 hover:text-white transition-colors duration-200 px-3 py-1.5 rounded-full border border-white/20">
                            {{ $platform }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="border-t border-white/10 mt-10 pt-6 text-center text-xs text-white/60">
            &copy; {{ date('Y') }} {{ $settings->name }}. Hak cipta dilindungi.
        </div>
    </div>
</footer>
