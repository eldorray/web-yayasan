<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\News;
use App\Models\PpdbInfo;
use App\Models\School;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $sekolahData = [
            ['name' => 'TK Islam Daarul Hikmah', 'level' => 'TK', 'established_year' => 1998, 'address' => 'Jl. Pesantren No.1, Jakarta'],
            ['name' => 'SD Islam Daarul Hikmah', 'level' => 'SD', 'established_year' => 2000, 'address' => 'Jl. Pesantren No.2, Jakarta'],
            ['name' => 'SMP Islam Al Madani', 'level' => 'SMP', 'established_year' => 2005, 'address' => 'Jl. Madani No.5, Bogor'],
        ];

        foreach ($sekolahData as $data) {
            $school = School::create(array_merge($data, [
                'description' => 'Sekolah unggulan yang memadukan kurikulum nasional dengan pendidikan agama Islam.',
                'phone' => '021-555-0100',
                'email' => strtolower(str_replace(' ', '', $data['name'])).'@dhm.id',
                'website_url' => 'https://'.strtolower(str_replace(' ', '-', $data['name'])).'.sch.id',
                'is_active' => true,
                'sort_order' => array_search($data['level'], ['TK', 'SD', 'SMP', 'SMA', 'SMK']),
            ]));

            PpdbInfo::create([
                'school_id' => $school->id,
                'academic_year' => '2026/2027',
                'open_date' => '2026-01-01',
                'close_date' => '2026-03-31',
                'requirements' => '<p>Fotokopi KK, Akta Kelahiran, Pas Foto 3x4, Ijazah/SKL</p>',
                'fees' => 'Rp 500.000',
                'registration_url' => $school->website_url.'/ppdb',
                'is_open' => $school->level !== 'SMP',
            ]);
        }

        News::factory()->count(6)->create([
            'author_id' => $admin->id,
        ]);

        News::factory()->count(3)->sequence(
            ['category' => 'yayasan', 'school_id' => null],
            ['category' => 'yayasan', 'school_id' => null],
            ['category' => 'yayasan', 'school_id' => null],
        )->create(['author_id' => $admin->id, 'title' => 'Wisuda Hafidz Angkatan 2026']);

        GalleryImage::factory()->count(8)->create();
        GalleryImage::factory()->count(4)->sequence(fn () => ['school_id' => School::inRandomOrder()->first()->id])->create();

        SiteSetting::updateOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
            'tagline' => 'Membina generasi Qur\'ani & berakhlak mulia.',
            'vision' => 'Menjadi pusat pendidikan Islam unggulan yang melahirkan generasi beriman, berilmu, dan berakhlak mulia.',
            'mission' => 'Menyelenggarakan pendidikan berbasis Al-Qur\'an dan Sunnah dengan kurikulum nasional berkualitas.',
            'address' => 'Jl. Pesantren No.1, Jakarta',
            'phone' => '021-555-0100',
            'email' => 'info@dhm.id',
            'established_year' => 1995,
            'students_count' => 2000,
            'socials' => [
                'facebook' => 'https://facebook.com/daarulhikmah',
                'instagram' => 'https://instagram.com/daarulhikmah',
                'youtube' => 'https://youtube.com/@daarulhikmah',
            ],
        ]);
    }
}
