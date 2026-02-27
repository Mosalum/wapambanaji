<?php
namespace Database\Factories;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
class MemberFactory extends Factory { protected $model = Member::class; public function definition(): array { return ['group_id'=>Group::factory(),'member_number'=>$this->faker->unique()->numerify('###'),'full_name'=>$this->faker->name(),'phone'=>'2557'.$this->faker->numerify('#######'),'join_date'=>now()->toDateString(),'status'=>'active']; }}
