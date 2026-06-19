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

    public function faviconMime(): string
    {
        if (! $this->logo) {
            return 'image/x-icon';
        }

        if (Str::startsWith($this->logo, ['http://', 'https://'])) {
            return 'image/png';
        }

        return match (strtolower(pathinfo($this->logo, PATHINFO_EXTENSION))) {
            'svg' => 'image/svg+xml',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            default => 'image/png',
        };
    }

    public function faviconVersion(): int
    {
        return $this->updated_at?->getTimestamp() ?? time();
    }

    public function syncPublicFavicon(): void
    {
        if (! $this->logo || Str::startsWith($this->logo, ['http://', 'https://'])) {
            return;
        }

        if (! Storage::disk('public')->exists($this->logo)) {
            return;
        }

        copy(
            Storage::disk('public')->path($this->logo),
            public_path('favicon.ico'),
        );
    }

    protected static function booted(): void
    {
        static::saved(function (SiteSetting $setting) {
            if ($setting->wasChanged('logo')) {
                $setting->syncPublicFavicon();
            }
        });
    }

    public static function current(): static
    {
        return static::firstOrCreate(['id' => 1], [
            'name' => 'Yayasan Pendidikan Daarul Hikmah Al Madani',
        ]);
    }
}
