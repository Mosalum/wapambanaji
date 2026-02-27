<?php

namespace Tests\Feature;

use App\Models\Contribution;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupScopingTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_is_scoped_by_group_id(): void
    {
        $groupA = Group::factory()->create();
        $groupB = Group::factory()->create();
        $memberA = Member::factory()->create(['group_id' => $groupA->id]);
        $memberB = Member::factory()->create(['group_id' => $groupB->id]);
        Contribution::factory()->create(['group_id' => $groupA->id, 'member_id' => $memberA->id]);
        Contribution::factory()->create(['group_id' => $groupB->id, 'member_id' => $memberB->id]);

        $this->assertEquals(1, Contribution::forGroup($groupA->id)->count());
    }
}
