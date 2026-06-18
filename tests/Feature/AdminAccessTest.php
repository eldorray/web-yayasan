<?php

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('redirects guest from admin routes to login', function () {
    get(route('admin.dashboard'))->assertRedirect(route('login'));
});

it('forbids non-admin user from admin dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);
    actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

it('allows admin user to access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    actingAs($admin)->get(route('admin.dashboard'))->assertOk();
});

it('admin dashboard shows school count', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    School::factory()->count(2)->create();
    actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Sekolah');
});
