<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageRatingPlatform extends Model
{
    protected $fillable = [
        'platform_name', 'logo_path', 'profile_url', 'rating_value',
        'review_count', 'verified_at', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'rating_value' => 'decimal:2',
        'review_count' => 'integer',
        'sort_order' => 'integer',
        'verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
