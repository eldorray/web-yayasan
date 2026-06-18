<!DOCTYPE html>
<html lang="id" data-theme="emerald">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="min-h-screen flex flex-col bg-surface-soft">
    <x-public.navbar />

    <main class="flex-1">
        {{ $slot }}
    </main>

    <x-public.footer />
    @livewireScripts
</body>
</html>
