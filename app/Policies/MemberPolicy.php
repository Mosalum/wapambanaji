<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function view(User $user, Member $member): bool
    {
        $isOwn = $user->memberships()->where('member_id', $member->id)->exists();
        return $isOwn || $user->hasRole($member->group_id, Role::GROUP_ADMIN) || $user->hasRole($member->group_id, Role::SECRETARY);
    }

    public function update(User $user, Member $member): bool
    {
        return $user->hasRole($member->group_id, Role::GROUP_ADMIN) || $user->hasRole($member->group_id, Role::SECRETARY);
    }
}
