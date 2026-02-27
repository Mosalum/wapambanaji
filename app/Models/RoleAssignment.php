<?php

namespace App\Models;

use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Model;

class RoleAssignment extends Model
{
    use BelongsToGroup;

    public $timestamps = false;

    protected $fillable = ['group_id', 'user_id', 'role', 'assigned_by', 'assigned_at'];

    protected $casts = ['assigned_at' => 'datetime'];
}
