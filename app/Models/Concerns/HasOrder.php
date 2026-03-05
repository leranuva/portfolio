<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasOrder
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }
}
