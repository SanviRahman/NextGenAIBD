<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = [
        'public_id', 'name', 'company_name', 'designation', 'phone', 'email',
        'message', 'source_page', 'status', 'consented_at', 'responded_at', 'internal_notes',
    ];

    protected $casts = [
        'consented_at' => 'datetime',
        'responded_at' => 'datetime',
    ];
}
