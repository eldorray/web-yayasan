@php $settings = \App\Models\SiteSetting::current(); @endphp

<footer class="relative mt-24 text-white overflow-hidden" style="background-color: var(--brand-900);">
    <div class="pattern-stars absolute inset-0 opacity-50" aria-hidden="true"></div>
    {{-- Gold hairline divider at the top --}}
    <div class="absolute top-0 inset-x-0 h-0.5" style="background: linear-gradient(90deg, transparent, var(--color-gold-500), transparent);" aria-hidden="true"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center font-display font-extrabold"
                          style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                    <span class="font-display font-bold">{{ $settings->name }}</span>
                </div>
                <p class="text-sm text-white/80">{{ $settings->tagline }}</p>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--color-gold-500);">Hubungi Kami</h4>
                <ul class="space-y-2 text-sm text-white/80">
                    @if ($settings->address)<li>{{ $settings->address }}</li>@endif
                    @if ($settings->phone)<li>{{ $settings->phone }}</li>@endif
                    @if ($settings->email)<li>{{ $settings->email }}</li>@endif
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-3 text-sm" style="color: var(--color-gold-500);">Tautan</h4>
                <ul class="space-y-2 text-sm text-white/80">
                    <li><a href="{{ route('public.schools.index') }}" class="hover:text-white">Sekolah Binaan</a></li>
                    <li><a href="{{ route('public.news.index') }}" class="hover:text-white">Berita</a></li>
                    <li><a href="{{ route('public.ppdb') }}" class="hover:text-white">PPDB</a></li>
                </ul>
                @if (! empty($settings->socials))
                    <div class="flex gap-3 mt-4">
                        @foreach ($settings->socials as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="text-white/80 hover:text-white capitalize text-sm">{{ $platform }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="border-t border-white/10 mt-8 pt-6 text-center text-xs text-white/60">
            &copy; {{ date('Y') }} {{ $settings->name }}. Hak cipta dilindungi.
        </div>
    </div>
</footer>
