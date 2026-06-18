<?php

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows published news list', function () {
    $author = User::factory()->create();
    News::factory()->create(['title' => 'Berita Uji Coba', 'is_published' => true, 'published_at' => now(), 'author_id' => $author->id]);
    get(route('public.news.index'))->assertOk()->assertSee('Berita Uji Coba');
});

it('hides draft news', function () {
    News::factory()->create(['title' => 'Draft Rahasia', 'is_published' => false]);
    get(route('public.news.index'))->assertDontSee('Draft Rahasia');
});

it('shows news article by slug', function () {
    $news = News::factory()->create(['title' => 'Artikel Lengkap', 'is_published' => true, 'published_at' => now()]);
    get(route('public.news.show', $news->slug))->assertOk()->assertSee('Artikel Lengkap');
});
