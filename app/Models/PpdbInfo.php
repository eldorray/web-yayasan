<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'academic_year', 'open_date', 'close_date',
        'requirements', 'fees', 'registration_url', 'is_open',
    ];

    protected $casts = [
        'open_date' => 'date',
        'close_date' => 'date',
        'is_open' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
