<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioConfig extends Model
{
    protected $fillable = [
        'name',
        'role',
        'summary',
        'values_style',
        'email',
        'linkedin_url',
        'github_url',
        'profile_image',
        'dark_mode_enabled',
    ];

    protected $casts = [
        'dark_mode_enabled' => 'boolean',
    ];
}
