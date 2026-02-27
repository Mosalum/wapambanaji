<?php
namespace Database\Factories;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class GroupMembershipFactory extends Factory { protected $model=GroupMembership::class; public function definition(): array { $group=Group::factory(); return ['user_id'=>User::factory(),'group_id'=>$group,'member_id'=>Member::factory(['group_id'=>$group]),'status'=>'active']; }}
