<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->admin = User::factory()->create(['is_admin' => true]));

it('shows gallery index to admin', function () {
    actingAs($this->admin)->get(route('admin.gallery.index'))->assertOk();
});
