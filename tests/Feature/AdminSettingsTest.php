<?php

use App\Livewire\Admin\Settings\Index;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows settings form to admin', function () {
    actingAs($this->admin)->get(route('admin.settings.index'))->assertOk();
});

it('updates site settings via livewire', function () {
    Livewire::actingAs($this->admin)->test(Index::class)
        ->set('tagline', 'Tagline Baru')
        ->call('save')
        ->assertHasNoErrors();

    expect(SiteSetting::current()->tagline)->toBe('Tagline Baru');
});
