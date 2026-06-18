@props(['school'])

<a href="{{ route('public.schools.show', $school->slug) }}"
   class="group card-lift block bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-soft">
    <div class="relative h-32 flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, var(--brand-800), var(--brand-600));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        @if ($school->logo_url)
            <img src="{{ $school->logo_url }}" alt="{{ $school->name }}" class="relative w-16 h-16 object-contain transition-transform duration-500 group-hover:scale-110">
        @else
            <span class="relative font-display text-4xl font-extrabold transition-transform duration-500 group-hover:scale-110" style="color: var(--color-gold-500);">{{ \Illuminate\Support\Str::limit($school->level, 2, '') }}</span>
        @endif
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $school->level }}</span>
            @if ($school->established_year)
                <span class="text-xs text-ink-500">Sejak {{ $school->established_year }}</span>
            @endif
        </div>
        <h3 class="font-display font-bold text-ink-900 group-hover:text-brand-700 transition-colors">{{ $school->name }}</h3>
        @if ($school->address)<p class="text-xs text-ink-500 mt-1 line-clamp-1">{{ $school->address }}</p>@endif
    </div>
</a>
