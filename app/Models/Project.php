<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasOrder;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'project_category_id',
        'url',
        'video_url',
        'order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        $path = ltrim($this->image, '/');
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }
}
