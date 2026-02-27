<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogService
{
    public function record(string $action, Model $model, ?array $before = null, ?array $after = null): void
    {
        AuditLog::create([
            'group_id' => $model->group_id ?? app('currentGroupId'),
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $model::class,
            'entity_id' => $model->getKey(),
            'before' => $before,
            'after' => $after,
            'ip_address' => request()->ip(),
        ]);
    }
}
