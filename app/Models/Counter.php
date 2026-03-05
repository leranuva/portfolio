<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasOrder;

    protected $fillable = [
        'label',
        'value',
        'icon',
        'suffix',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'order' => 'integer',
        ];
    }
}
