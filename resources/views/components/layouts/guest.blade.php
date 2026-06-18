@props(['title' => null])

@php
    // Default theme for guest pages. If later stored globally, swap this source.
    $theme = 'emerald';
    $appearance = 'system';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-theme="{{ $theme }}"
    data-appearance="{{ $appearance }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <script>
        (function () {
            const html = document.documentElement;
            const mode = html.dataset.appearance || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldDark = mode === 'dark' || (mode === 'system' && prefersDark);
            html.classList.toggle('dark', shouldDark);
        })();
    </script>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-surface text-ink-800 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 md:p-8">
        <div
            class="w-full max-w-5xl bg-white rounded-[2rem] shadow-soft overflow-hidden grid grid-cols-1 lg:grid-cols-2 min-h-[640px]">
            {{-- Left panel: brand / showcase --}}
            <div
                class="relative hidden lg:flex flex-col justify-between p-10 bg-gradient-to-br from-brand-500 via-brand-600 to-[#422a18] text-white overflow-hidden">
                <div class="absolute -top-16 -right-20 w-80 h-80 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-10 w-72 h-72 rounded-full bg-black/20 blur-3xl"></div>

                <div class="relative flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-white text-brand-500 flex items-center justify-center font-bold text-lg">
                        .I</div>
                    <span class="font-bold text-lg tracking-tight">{{ config('app.name', 'InvestIQ') }}</span>
                </div>

                <div class="relative space-y-6">
                    <h2 class="text-3xl font-bold leading-tight">Control your investment, income, and expenses.</h2>
                    <p class="text-white/80 text-sm leading-relaxed">A clean, focused admin panel to track performance,
                        review activity, and stay on top of every transaction.</p>

                    <div class="flex items-center gap-3 pt-4">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-white/20 border-2 border-white/40"></div>
                            <div class="w-8 h-8 rounded-full bg-white/30 border-2 border-white/40"></div>
                            <div class="w-8 h-8 rounded-full bg-white/40 border-2 border-white/40"></div>
                        </div>
                        <p class="text-xs text-white/80">Trusted by ambitious teams building smarter finance tools.</p>
                    </div>
                </div>

                <div class="relative text-xs text-white/70">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>

            {{-- Right panel: form slot --}}
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div
                        class="w-10 h-10 rounded-xl bg-ink-900 text-white flex items-center justify-center font-bold text-lg">
                        .I</div>
                    <span
                        class="font-bold text-lg tracking-tight text-ink-900">{{ config('app.name', 'InvestIQ') }}</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>