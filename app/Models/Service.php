<?php

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasOrder;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
