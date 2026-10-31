<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageFeaturedItem extends Model
{
    protected $fillable = ['section_key', 'item_type', 'item_id', 'sort_order', 'is_active'];

    protected $casts = ['item_id' => 'integer', 'sort_order' => 'integer', 'is_active' => 'boolean'];
}
