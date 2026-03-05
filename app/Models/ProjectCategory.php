<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectCategory extends Model
{
    use HasOrder;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'order',
    ];

    protected function getSlugSourceColumn(): string
    {
        return 'name';
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
