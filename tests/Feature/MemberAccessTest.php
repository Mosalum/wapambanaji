<?php

namespace Tests\Feature;

use App\Models\GroupMembership;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_cannot_view_other_member_without_role(): void
    {
        $user = User::factory()->create();
        $mine = Member::factory()->create();
        $other = Member::factory()->create(['group_id' => $mine->group_id]);
        GroupMembership::factory()->create(['user_id' => $user->id, 'group_id' => $mine->group_id, 'member_id' => $mine->id]);

        $this->actingAs($user)->get(route('members.show', $other))->assertForbidden();
    }
}
