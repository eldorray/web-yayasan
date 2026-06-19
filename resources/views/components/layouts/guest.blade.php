@props(['title' => null])

@php
    $theme = 'emerald';
    $settings = \App\Models\SiteSetting::current();
@endphp

<!DOCTYPE html>
<html lang="id" data-theme="{{ $theme }}" data-appearance="light" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' — ' : '' }}{{ config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="auth-page min-h-screen bg-surface-soft text-ink-800 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="auth-shell w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 min-h-[600px] lg:min-h-[640px]">
            {{-- Brand panel --}}
            <div class="auth-brand-panel relative hidden lg:flex flex-col justify-between p-10 xl:p-12 text-white overflow-hidden">
                <div class="pattern-stars absolute inset-0 opacity-50" aria-hidden="true"></div>
                <div class="absolute -top-20 -right-16 w-72 h-72 rounded-full blur-3xl opacity-25" style="background: var(--color-gold-500);" aria-hidden="true"></div>

                <div class="relative flex items-center gap-3">
                    @if ($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo {{ $settings->name }}" class="w-11 h-11 rounded-xl object-contain bg-white p-1 shadow-sm">
                    @else
                        <span class="w-11 h-11 rounded-xl flex items-center justify-center font-display font-extrabold text-lg shadow-sm"
                              style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                    @endif
                    <span class="font-display font-bold text-base leading-snug max-w-[16rem]">{{ $settings->name }}</span>
                </div>

                <div class="relative space-y-5 max-w-md">
                    <p class="eyebrow" style="color: var(--color-gold-500);">Panel Admin</p>
                    <h2 class="font-display text-3xl xl:text-4xl font-extrabold leading-tight">
                        Kelola website yayasan dari satu tempat.
                    </h2>
                    <p class="text-white/85 text-sm leading-relaxed">
                        Sekolah, berita, galeri, dan pengaturan — semua terpusat dalam dashboard yang rapi.
                    </p>

                    <ul class="space-y-3 pt-2">
                        @foreach (['Kelola sekolah binaan', 'Publikasi berita & galeri', 'Atur profil yayasan'] as $item)
                            <li class="flex items-center gap-2.5 text-sm text-white/90">
                                <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0" style="background: rgba(244, 211, 94, 0.2); color: var(--color-gold-400);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                </span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <p class="relative text-xs text-white/60">
                    &copy; {{ date('Y') }} {{ $settings->name }}
                </p>
            </div>

            {{-- Form panel --}}
            <div class="auth-form-panel p-8 sm:p-10 lg:p-12 flex flex-col justify-center">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    @if ($settings->logo_url)
                        <img src="{{ $settings->logo_url }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-white border border-ink-100 p-0.5">
                    @else
                        <span class="w-10 h-10 rounded-xl flex items-center justify-center font-display font-extrabold text-base"
                              style="background: radial-gradient(circle at 30% 30%, var(--color-gold-300), var(--color-gold-600)); color: var(--brand-900);">Dh</span>
                    @endif
                    <span class="font-display font-bold text-ink-900 text-sm leading-snug">{{ \Illuminate\Support\Str::limit($settings->name, 32) }}</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
