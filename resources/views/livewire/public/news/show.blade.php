<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('public.news.index') }}" class="text-sm hover:underline" style="color: var(--brand-700);">&larr; Semua Berita</a>

    <article class="mt-4">
        <span class="text-xs font-semibold px-2 py-1 rounded-full uppercase" style="background-color: var(--color-gold-100); color: var(--color-gold-800);">{{ $news->category }}</span>
        <h1 class="text-3xl font-bold text-ink-900 mt-3">{{ $news->title }}</h1>
        <p class="text-sm text-ink-500 mt-2">
            {{ $news->published_at?->format('d M Y') }} &middot; {{ $news->reading_time }} min baca
            @if ($news->author) &middot; {{ $news->author->name }} @endif
            @if ($news->school) &middot; <a href="{{ route('public.schools.show', $news->school->slug) }}" class="hover:underline">{{ $news->school->name }}</a> @endif
        </p>

        @if ($news->cover_image_url)
            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="w-full h-64 object-cover rounded-2xl mt-6 shadow-soft">
        @endif

        @if ($news->excerpt)
            <p class="text-lg text-ink-700 mt-6 font-medium leading-relaxed">{{ $news->excerpt }}</p>
        @endif

        <div class="prose prose-ink max-w-none mt-6">
            {!! $news->body !!}
        </div>
    </article>
</div>
