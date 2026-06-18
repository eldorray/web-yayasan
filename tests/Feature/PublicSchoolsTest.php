<?php

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('shows school list page', function () {
    School::factory()->create(['name' => 'SD Uji Coba', 'level' => 'SD', 'is_active' => true]);
    get(route('public.schools.index'))->assertOk()->assertSee('SD Uji Coba');
});

it('hides inactive schools', function () {
    School::factory()->create(['name' => 'Sekolah Nonaktif', 'is_active' => false]);
    get(route('public.schools.index'))->assertDontSee('Sekolah Nonaktif');
});

it('shows school profile by slug', function () {
    $school = School::factory()->create(['name' => 'SMA Tunas Bangsa', 'level' => 'SMA']);
    get(route('public.schools.show', $school->slug))->assertOk()->assertSee('SMA Tunas Bangsa');
});

it('returns 404 for unknown slug', function () {
    get(route('public.schools.show', 'tidak-ada'))->assertNotFound();
});
