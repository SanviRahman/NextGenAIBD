<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MetaPixelIntegration extends Model
{
    protected $fillable = [
        'name',
        'pixel_id',
        'pixel_script',
        'access_token',
        'test_event_code',
        'is_active',
    ];

    protected $hidden = [
        'access_token',
        'pixel_script',
    ];

    protected $casts = [
        // Laravel encrypts this value at rest using APP_KEY.
        'access_token' => 'encrypted',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
