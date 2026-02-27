<?php

namespace App\Models;

use App\Models\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory, BelongsToGroup;

    protected $fillable = ['group_id', 'member_number', 'full_name', 'phone', 'address', 'join_date', 'status', 'kyc_data'];
    protected $casts = ['join_date' => 'date', 'kyc_data' => 'array'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
