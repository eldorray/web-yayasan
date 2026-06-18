<?php

use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates slug from school name', function () {
    $school = School::create(['name' => 'SD Islam Daarul Hikmah', 'level' => 'SD']);
    expect($school->slug)->toBe('sd-islam-daarul-hikmah');
});

it('computes school logo url', function () {
    $school = School::create(['name' => 'SMP Al Madani', 'level' => 'SMP', 'logo' => 'logos/smp.png']);
    expect($school->logo_url)->toContain('/storage/logos/smp.png');
});

it('returns null logo url when no logo', function () {
    $school = School::create(['name' => 'SMA 1', 'level' => 'SMA']);
    expect($school->logo_url)->toBeNull();
});

it('scopes news to published only', function () {
    $author = User::factory()->create();
    News::create(['title' => 'Draft', 'author_id' => $author->id, 'is_published' => false]);
    News::create(['title' => 'Live', 'author_id' => $author->id, 'is_published' => true, 'published_at' => now()]);
    expect(News::published()->count())->toBe(1);
});

it('returns singleton site setting', function () {
    $settings = SiteSetting::current();
    expect($settings)->toBeInstanceOf(SiteSetting::class)
        ->and($settings->name)->toBe('Yayasan Pendidikan Daarul Hikmah Al Madani');
});
