<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = [
        'title',
        'title_en',
        'description_en',
        'content_en',
        'slug',
        'description',
        'content',
        'cover_path',
        'target_amount',
        'collected_amount',
        'status',
        'started_at',
        'ended_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'started_at' => 'date',
            'ended_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Program $program) {
            if (blank($program->slug)) {
                $program->slug = Str::slug($program->title);
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProgramImage::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function progress(): int
    {
        if (! $this->target_amount || $this->target_amount <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->collected_amount / $this->target_amount) * 100));
    }
}
