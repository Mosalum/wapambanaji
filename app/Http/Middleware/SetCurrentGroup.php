<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCurrentGroup
{
    public function handle(Request $request, Closure $next)
    {
        $groupId = $request->route('group') ?? $request->header('X-Group-Id') ?? $request->user()?->memberships()->value('group_id');
        if ($groupId) {
            app()->instance('currentGroupId', (int) $groupId);
        }

        return $next($request);
    }
}
