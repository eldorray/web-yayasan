@props(['news'])

<a href="{{ route('public.news.show', $news->slug) }}"
   class="group block bg-white rounded-2xl border border-ink-100 overflow-hidden shadow-soft hover:shadow-md transition-shadow">
    <div class="h-36 flex items-center justify-center" style="background: linear-gradient(135deg, var(--brand-600), var(--brand-800));">
        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
        @else
            <span class="text-3xl font-bold text-white/40">&#128240;</span>
        @endif
    </div>
    <div class="p-4">
        <span class="text-xs font-semibold px-2 py-0.5 rounded-full uppercase" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $news->category }}</span>
        <h3 class="font-semibold text-ink-900 mt-2 group-hover:text-brand-700 line-clamp-2">{{ $news->title }}</h3>
        <p class="text-xs text-ink-500 mt-2">{{ $news->published_at?->format('d M Y') }} · {{ $news->reading_time }} min baca</p>
    </div>
</a>
