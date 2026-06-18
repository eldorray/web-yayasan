<?php

use App\Livewire\Admin\News\Form;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows news index to admin', function () {
    actingAs($this->admin)->get(route('admin.news.index'))->assertOk();
});

it('creates news with author set to current admin', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('title', 'Berita Baru')
        ->set('category', 'yayasan')
        ->set('body', '<p>Isi berita</p>')
        ->set('is_published', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.news.index'));

    $news = News::where('title', 'Berita Baru')->first();
    expect($news)->not->toBeNull()
        ->and($news->author_id)->toBe($this->admin->id);
});

it('validates required title', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title']);
});
