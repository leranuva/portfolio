<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'problem_solution',
        'role',
        'technologies',
        'demo_url',
        'repository_url',
        'live_url',
        'results_learnings',
        'image',
        'order',
        'featured',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'order' => 'integer',
    ];
}
