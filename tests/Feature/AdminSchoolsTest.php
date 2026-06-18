<?php

use App\Livewire\Admin\Schools\Form;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows school index to admin', function () {
    actingAs($this->admin)->get(route('admin.schools.index'))->assertOk();
});

it('creates a school via livewire', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('name', 'SMA Baru')
        ->set('level', 'SMA')
        ->set('address', 'Jl. Test')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.schools.index'));

    expect(School::where('name', 'SMA Baru')->exists())->toBeTrue();
});

it('validates required fields', function () {
    Livewire::actingAs($this->admin)->test(Form::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});
