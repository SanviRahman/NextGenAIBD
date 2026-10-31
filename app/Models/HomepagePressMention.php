<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepagePressMention extends Model
{
    protected $fillable = ['name', 'logo_path', 'website_url', 'alt_text', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'sort_order' => 'integer'];
}
