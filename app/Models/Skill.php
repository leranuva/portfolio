<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasOrder;

    protected $fillable = [
        'name',
        'percentage',
        'icon',
        'category',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'integer',
            'order' => 'integer',
        ];
    }
}
