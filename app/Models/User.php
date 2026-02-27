<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'pin_hash', 'last_login_at'];

    protected $hidden = ['password', 'remember_token', 'pin_hash'];

    public function memberships(): HasMany
    {
        return $this->hasMany(GroupMembership::class);
    }

    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleAssignment::class);
    }

    public function hasRole(int $groupId, Role $role): bool
    {
        return $this->roleAssignments()->where('group_id', $groupId)->where('role', $role->value)->exists();
    }
}
