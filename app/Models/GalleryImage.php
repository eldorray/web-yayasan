<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image', 'caption', 'school_id', 'sort_order',
    ];

    protected $casts = ['sort_order' => 'integer'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            return Storage::disk('public')->url($this->image);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->latest();
    }
}
