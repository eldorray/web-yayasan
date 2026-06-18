@props(['news'])

<a href="{{ route('public.news.show', $news->slug) }}"
   class="group card-lift block bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-soft">
    <div class="relative h-36 flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, var(--brand-600), var(--brand-800));">
        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
            <span class="relative font-display text-2xl font-extrabold text-white/30">{{ \Illuminate\Support\Str::limit($news->category, 3, '') }}</span>
        @endif
        <span class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wide backdrop-blur" style="background-color: rgba(244,211,94,0.9); color: var(--brand-900);">{{ $news->category }}</span>
    </div>
    <div class="p-4">
        <h3 class="font-display font-bold text-ink-900 group-hover:text-brand-700 line-clamp-2 transition-colors">{{ $news->title }}</h3>
        <p class="text-xs text-ink-500 mt-2">{{ $news->published_at?->format('d M Y') }} &middot; {{ $news->reading_time }} min baca</p>
    </div>
</a>
