<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageConsultationSetting extends Model
{
    protected $fillable = [
        'eyebrow', 'heading', 'description', 'image_path', 'image_alt',
        'background_color', 'accent_color', 'is_active',
    ];
    protected $casts = ['is_active' => 'boolean'];
}
