@props(['school'])

<a href="{{ route('public.schools.show', $school->slug) }}" wire:navigate
   class="group card-lift cursor-pointer block public-card overflow-hidden">
    <div class="relative h-36 flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, var(--brand-800), var(--brand-600));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        @if ($school->logo_url)
            <img src="{{ $school->logo_url }}" alt="Logo {{ $school->name }}" class="relative w-16 h-16 object-contain transition-transform duration-500 group-hover:scale-110">
        @else
            <span class="relative font-display text-4xl font-extrabold transition-transform duration-500 group-hover:scale-110" style="color: var(--color-gold-500);">{{ \Illuminate\Support\Str::limit($school->level, 2, '') }}</span>
        @endif
        <span class="absolute top-3 right-3 public-badge public-badge-gold">{{ $school->level }}</span>
    </div>
    <div class="p-5 space-y-2">
        <h3 class="font-display font-bold text-ink-900 group-hover:text-brand-700 transition-colors duration-200">{{ $school->name }}</h3>
        @if ($school->address)
            <p class="flex items-start gap-1.5 text-xs text-ink-600 line-clamp-2">
                <x-public.icon name="map-pin" class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                {{ $school->address }}
            </p>
        @endif
        @if ($school->established_year)
            <p class="flex items-center gap-1.5 text-xs text-ink-600">
                <x-public.icon name="calendar" class="w-3.5 h-3.5 shrink-0" />
                Sejak {{ $school->established_year }}
            </p>
        @endif
        <span class="public-link text-xs pt-1">
            Lihat profil
            <x-public.icon name="arrow-right" class="w-3.5 h-3.5" />
        </span>
    </div>
</a>
