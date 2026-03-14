<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Get the public URL for the post image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        $path = ltrim($this->image, '/');
        // blog disk saves to public/blog/, legacy used storage/app/public/blog/
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }
}
