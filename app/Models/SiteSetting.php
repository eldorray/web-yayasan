<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    protected function logoUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->logo) {
                return null;
            }

            if (Str::startsWith($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }

            return Storage::disk('public')->url($this->logo);
        });
    }

    public static function current(): static
    {
        return static::firstOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
        ]);
    }
}
