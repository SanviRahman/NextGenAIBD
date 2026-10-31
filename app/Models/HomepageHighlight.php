<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageHighlight extends Model
{
    protected $fillable = [
        'hero_slide_id', 'title', 'subtitle', 'icon_path', 'display_value',
        'link_url', 'sort_order', 'is_active',
    ];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];

    public function slide(): BelongsTo
    {
        return $this->belongsTo(HomepageHeroSlide::class, 'hero_slide_id');
    }
}
