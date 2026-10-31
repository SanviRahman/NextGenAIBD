<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageServiceSetting extends Model
{
    protected $fillable = [
        'eyebrow', 'heading', 'description', 'button_label',
        'background_color', 'card_color', 'accent_color',
        'cards_limit', 'is_active',
    ];

    protected $casts = ['cards_limit' => 'integer', 'is_active' => 'boolean'];
}
