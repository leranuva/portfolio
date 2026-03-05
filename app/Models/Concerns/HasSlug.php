<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model): void {
            if (empty($model->{$model->getSlugColumn()}) && ! empty($model->{$model->getSlugSourceColumn()})) {
                $model->{$model->getSlugColumn()} = Str::slug($model->{$model->getSlugSourceColumn()});
            }
        });

        static::updating(function (Model $model): void {
            if ($model->isDirty($model->getSlugSourceColumn()) && empty($model->{$model->getSlugColumn()})) {
                $model->{$model->getSlugColumn()} = Str::slug($model->{$model->getSlugSourceColumn()});
            }
        });
    }

    protected function getSlugColumn(): string
    {
        return 'slug';
    }

    protected function getSlugSourceColumn(): string
    {
        return 'title';
    }
}
