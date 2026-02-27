<?php
namespace Database\Factories;
use App\Models\Contribution;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
class ContributionFactory extends Factory { protected $model = Contribution::class; public function definition(): array { $group=Group::factory(); return ['group_id'=>$group,'member_id'=>Member::factory(['group_id'=>$group]),'contribution_type_id'=>1,'amount'=>10000,'paid_at'=>now()]; }}
