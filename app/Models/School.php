<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'level', 'description', 'address',
        'map_lat', 'map_lng', 'phone', 'email', 'logo',
        'cover_image', 'website_url', 'established_year',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'map_lat' => 'decimal:7',
        'map_lng' => 'decimal:7',
        'established_year' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (School $school) {
            $school->slug ??= Str::slug($school->name);
        });

        static::updating(function (School $school) {
            if ($school->isDirty('name') && ! $school->isDirty('slug')) {
                $school->slug = Str::slug($school->name);
            }
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function ppdbInfos(): HasMany
    {
        return $this->hasMany(PpdbInfo::class);
    }

    public function activePpdb(): ?PpdbInfo
    {
        return $this->ppdbInfos()->where('is_open', true)->latest('academic_year')->first();
    }

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

    protected function coverImageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->cover_image) {
                return null;
            }

            return Storage::disk('public')->url($this->cover_image);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
