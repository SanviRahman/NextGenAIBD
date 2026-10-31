<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageHeroSetting extends Model
{
    protected $fillable = [
        'primary_cta_label', 'primary_cta_url', 'secondary_cta_label', 'secondary_cta_url',
        'background_color', 'accent_color', 'text_color', 'autoplay_enabled',
    ];

    protected $casts = ['autoplay_enabled' => 'boolean'];
}
