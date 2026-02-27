<?php

namespace App\Models;

use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use BelongsToGroup;

    protected $fillable = [
        'group_id', 'user_id', 'action', 'entity_type', 'entity_id', 'before', 'after', 'ip_address',
    ];

    protected $casts = ['before' => 'array', 'after' => 'array'];
}
