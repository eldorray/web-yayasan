@php $settings = \App\Models\SiteSetting::current(); @endphp

@if ($settings->logo_url)
    <link rel="icon" href="{{ $settings->logo_url }}?v={{ $settings->faviconVersion() }}" type="{{ $settings->faviconMime() }}">
    <link rel="apple-touch-icon" href="{{ $settings->logo_url }}?v={{ $settings->faviconVersion() }}">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
@endif
