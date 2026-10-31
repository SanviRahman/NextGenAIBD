<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSpotlightVideo extends Model
{
    protected $fillable = [
        'title', 'poster_path', 'preview_video_path', 'full_video_provider',
        'full_video_url', 'caption', 'sort_order', 'is_active',
    ];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}
