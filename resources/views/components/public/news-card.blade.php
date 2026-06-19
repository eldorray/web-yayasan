@props(['news', 'featured' => false])

<a href="{{ route('public.news.show', $news->slug) }}" wire:navigate
   @class([
       'group card-lift cursor-pointer block public-card overflow-hidden',
       'public-featured-news' => $featured,
   ])>
    <div @class([
        'relative flex items-center justify-center overflow-hidden',
        'h-36' => ! $featured,
        'h-48 sm:h-56' => $featured,
    ]) style="background: linear-gradient(135deg, var(--brand-600), var(--brand-800));">
        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}"
                 @class(['w-full h-full object-cover transition-transform duration-500 group-hover:scale-105', 'public-featured-news__image' => $featured])>
        @else
            <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
            <span class="relative font-display text-2xl font-extrabold text-white/30">{{ \Illuminate\Support\Str::limit($news->category, 3, '') }}</span>
        @endif
        <span class="absolute top-3 left-3 public-badge public-badge-gold uppercase tracking-wide">{{ $news->category }}</span>
    </div>
    <div @class(['p-5 space-y-2', 'sm:p-6' => $featured])>
        <h3 @class([
            'font-display font-bold text-ink-900 group-hover:text-brand-700 transition-colors duration-200',
            'line-clamp-2' => ! $featured,
            'text-xl sm:text-2xl line-clamp-3' => $featured,
        ])>{{ $news->title }}</h3>
        @if ($featured && $news->excerpt)
            <p class="text-sm text-ink-600 line-clamp-2">{{ $news->excerpt }}</p>
        @endif
        <p class="flex items-center gap-1.5 text-xs text-ink-600">
            <x-public.icon name="calendar" class="w-3.5 h-3.5 shrink-0" />
            {{ $news->published_at?->format('d M Y') }} &middot; {{ $news->reading_time }} min baca
        </p>
    </div>
</a>
