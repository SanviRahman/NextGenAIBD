<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSpotlightSetting extends Model
{
    protected $fillable = [
        'heading', 'eyebrow', 'description', 'background_color', 'accent_color',
        'logo_scroll_seconds', 'logo_autoplay', 'video_autoplay', 'is_active',
    ];

    protected $casts = [
        'logo_autoplay' => 'boolean', 'video_autoplay' => 'boolean',
        'is_active' => 'boolean', 'logo_scroll_seconds' => 'integer',
    ];
}
