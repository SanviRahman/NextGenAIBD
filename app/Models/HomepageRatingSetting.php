<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageRatingSetting extends Model
{
    protected $fillable = [
        'eyebrow', 'heading', 'description', 'background_color', 'accent_color',
        'scroll_seconds', 'autoplay', 'is_active',
    ];

    protected $casts = [
        'scroll_seconds' => 'integer',
        'autoplay' => 'boolean',
        'is_active' => 'boolean',
    ];
}
