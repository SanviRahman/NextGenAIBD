<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomepageHeroSlide extends Model
{
    protected $fillable = [
        'internal_name', 'main_image_path', 'main_image_alt', 'background_shape_path',
        'caption_text', 'caption_animation', 'display_duration_ms', 'sort_order', 'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'display_duration_ms' => 'integer',
    ];

    public function highlights(): HasMany
    {
        return $this->hasMany(HomepageHighlight::class, 'hero_slide_id');
    }
}
