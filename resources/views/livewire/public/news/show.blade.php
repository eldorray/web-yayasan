<div class="public-container-narrow py-10 sm:py-12">
    <a href="{{ route('public.news.index') }}" wire:navigate class="public-back-link">
        <x-public.icon name="arrow-left" class="w-4 h-4" />
        Semua Berita
    </a>

    <article class="mt-6">
        <span class="public-badge public-badge-gold uppercase tracking-wide">{{ $news->category }}</span>
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-ink-900 mt-4 leading-tight">{{ $news->title }}</h1>

        <div class="public-article-meta mt-4 pt-4 border-t border-ink-100">
            <span class="flex items-center gap-1.5">
                <x-public.icon name="calendar" class="w-4 h-4 shrink-0" />
                {{ $news->published_at?->format('d M Y') }}
            </span>
            <span>{{ $news->reading_time }} min baca</span>
            @if ($news->author)
                <span>{{ $news->author->name }}</span>
            @endif
            @if ($news->school)
                <a href="{{ route('public.schools.show', $news->school->slug) }}" wire:navigate>{{ $news->school->name }}</a>
            @endif
        </div>

        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-56 sm:h-72 object-cover rounded-[2rem] mt-8 shadow-soft">
        @endif

        @if ($news->excerpt)
            <p class="text-lg text-ink-700 mt-8 font-medium leading-relaxed border-l-4 pl-5" style="border-color: var(--color-gold-500);">{{ $news->excerpt }}</p>
        @endif

        <div class="prose prose-ink max-w-none mt-8 text-ink-700 leading-relaxed">
            {!! $news->body !!}
        </div>
    </article>
</div>
