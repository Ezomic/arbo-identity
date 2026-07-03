<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public const UPDATED_AT = null; // append-only

    protected $fillable = [
        'user_id', 'event', 'ip_address', 'user_agent', 'payload', 'checksum',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
