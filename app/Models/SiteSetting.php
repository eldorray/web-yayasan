<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name', 'tagline', 'vision', 'mission', 'history',
        'address', 'phone', 'email', 'logo', 'established_year',
        'students_count', 'socials',
    ];

    protected $casts = [
        'socials' => 'array',
        'established_year' => 'integer',
        'students_count' => 'integer',
    ];

    public static function current(): static
    {
        return static::firstOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
        ]);
    }
}
