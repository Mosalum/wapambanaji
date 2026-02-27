<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\Member;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $group = Group::create(['name' => 'Upendo VICOBA - Dar', 'slug' => 'upendo-dar', 'currency' => 'TZS', 'theme_color' => '#0f172a']);

        $members = collect(range(1, 12))->map(fn (int $i) => Member::create([
            'group_id' => $group->id,
            'member_number' => str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            'full_name' => "Member {$i}",
            'phone' => '255700000'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            'join_date' => now()->subMonths(14 - $i)->toDateString(),
            'status' => 'active',
        ]));

        $accounts = [
            ['Super Admin', 'admin@vicoba.test', '255700100001', null, [Role::SUPER_ADMIN]],
            ['Group Admin', 'groupadmin@vicoba.test', '255700100002', 1, [Role::GROUP_ADMIN, Role::MEMBER]],
            ['Treasurer', 'treasurer@vicoba.test', '255700100003', 2, [Role::TREASURER, Role::MEMBER]],
            ['Secretary', 'secretary@vicoba.test', '255700100004', 3, [Role::SECRETARY, Role::MEMBER]],
            ['Loan Officer', 'loanofficer@vicoba.test', '255700100005', 4, [Role::LOAN_OFFICER, Role::MEMBER]],
            ['Auditor', 'auditor@vicoba.test', '255700100006', 5, [Role::AUDITOR, Role::MEMBER]],
        ];
        foreach (range(6, 12) as $i) { $accounts[] = ["Member User {$i}", "member{$i}@vicoba.test", '2557001000'.str_pad((string)$i,2,'0',STR_PAD_LEFT), $i, [Role::MEMBER]]; }

        foreach ($accounts as [$name, $email, $phone, $memberIndex, $roles]) {
            $user = User::create(['name'=>$name,'email'=>$email,'phone'=>$phone,'password'=>Hash::make('Password123!'),'pin_hash'=>Hash::make('1234')]);
            GroupMembership::create(['user_id'=>$user->id,'group_id'=>$group->id,'member_id'=>$memberIndex ? $members[$memberIndex-1]->id : null,'status'=>'active']);
            foreach ($roles as $role) { RoleAssignment::create(['group_id'=>$group->id,'user_id'=>$user->id,'role'=>$role->value,'assigned_by'=>1,'assigned_at'=>now()]); }
        }

        $meetingIds = [];
        foreach (range(1,3) as $i) {
            $meetingIds[] = DB::table('meetings')->insertGetId(['group_id'=>$group->id,'meeting_date'=>now()->subWeeks(4-$i)->toDateString(),'venue'=>'Temeke Hall','agenda'=>'Weekly collection','minutes'=>'Meeting notes','is_closed'=>false,'created_at'=>now(),'updated_at'=>now()]);
        }

        foreach ($members as $member) {
            foreach ($meetingIds as $mid) { DB::table('attendance')->insert(['group_id'=>$group->id,'meeting_id'=>$mid,'member_id'=>$member->id,'present'=>true,'created_at'=>now(),'updated_at'=>now()]); }
        }

        $savingsId = DB::table('contribution_types')->insertGetId(['group_id'=>$group->id,'name'=>'Savings','default_amount'=>20000,'created_at'=>now(),'updated_at'=>now()]);
        $sharesId = DB::table('contribution_types')->insertGetId(['group_id'=>$group->id,'name'=>'Shares','default_amount'=>10000,'created_at'=>now(),'updated_at'=>now()]);
        $welfareId = DB::table('contribution_types')->insertGetId(['group_id'=>$group->id,'name'=>'Welfare','default_amount'=>5000,'created_at'=>now(),'updated_at'=>now()]);

        foreach ([$savingsId,$sharesId,$welfareId] as $ct) {
            DB::table('contributions')->insert(['group_id'=>$group->id,'member_id'=>$members[0]->id,'meeting_id'=>$meetingIds[0],'contribution_type_id'=>$ct,'amount'=>15000,'paid_at'=>now()->subWeeks(3),'created_at'=>now(),'updated_at'=>now()]);
        }

        $flatId = DB::table('loan_products')->insertGetId(['group_id'=>$group->id,'name'=>'Biashara Flat','interest_type'=>'flat','interest_rate'=>10,'duration_months'=>6,'rules'=>json_encode(['min_savings_multiple'=>2]),'created_at'=>now(),'updated_at'=>now()]);
        $redId = DB::table('loan_products')->insertGetId(['group_id'=>$group->id,'name'=>'Maendeleo Reducing','interest_type'=>'reducing','interest_rate'=>12,'duration_months'=>8,'rules'=>json_encode(['max_multiple'=>3]),'created_at'=>now(),'updated_at'=>now()]);

        $loan1 = DB::table('loans')->insertGetId(['group_id'=>$group->id,'member_id'=>$members[1]->id,'loan_product_id'=>$flatId,'status'=>'Active','principal'=>500000,'interest_rate'=>10,'duration_months'=>6,'disbursed_at'=>now()->subMonths(2),'created_at'=>now(),'updated_at'=>now()]);
        $loan2 = DB::table('loans')->insertGetId(['group_id'=>$group->id,'member_id'=>$members[7]->id,'loan_product_id'=>$redId,'status'=>'Overdue','principal'=>300000,'interest_rate'=>12,'duration_months'=>4,'disbursed_at'=>now()->subMonths(5),'created_at'=>now(),'updated_at'=>now()]);

        DB::table('repayments')->insert(['group_id'=>$group->id,'loan_id'=>$loan1,'member_id'=>$members[1]->id,'idempotency_key'=>'seed-r1','amount'=>100000,'penalty_amount'=>0,'paid_at'=>now()->subMonth(),'created_at'=>now(),'updated_at'=>now()]);
        DB::table('repayments')->insert(['group_id'=>$group->id,'loan_id'=>$loan2,'member_id'=>$members[7]->id,'idempotency_key'=>'seed-r2','amount'=>30000,'penalty_amount'=>5000,'paid_at'=>now()->subMonths(2),'created_at'=>now(),'updated_at'=>now()]);

        $fineType = DB::table('fine_types')->insertGetId(['group_id'=>$group->id,'name'=>'Late Attendance','default_amount'=>2000,'created_at'=>now(),'updated_at'=>now()]);
        $fineId = DB::table('fines')->insertGetId(['group_id'=>$group->id,'member_id'=>$members[5]->id,'fine_type_id'=>$fineType,'amount'=>2000,'reason'=>'Late','status'=>'partial','applied_at'=>now()->subWeek(),'created_at'=>now(),'updated_at'=>now()]);
        DB::table('fine_payments')->insert(['group_id'=>$group->id,'fine_id'=>$fineId,'amount'=>1000,'paid_at'=>now()->subDays(2),'created_at'=>now(),'updated_at'=>now()]);

        $cat = DB::table('expense_categories')->insertGetId(['group_id'=>$group->id,'name'=>'Stationery','created_at'=>now(),'updated_at'=>now()]);
        DB::table('expenses')->insert(['group_id'=>$group->id,'expense_category_id'=>$cat,'amount'=>25000,'narration'=>'Receipt books','incurred_at'=>now()->subDays(4),'created_at'=>now(),'updated_at'=>now()]);
        DB::table('incomes')->insert(['group_id'=>$group->id,'amount'=>120000,'source'=>'Donation','received_at'=>now()->subDays(5),'created_at'=>now(),'updated_at'=>now()]);

        $cycleId = DB::table('work_cycles')->insertGetId(['group_id'=>$group->id,'name'=>'2026 Q1','start_date'=>now()->startOfQuarter()->toDateString(),'end_date'=>now()->endOfQuarter()->toDateString(),'frequency'=>'quarterly','status'=>'open','created_at'=>now(),'updated_at'=>now()]);
        DB::table('notifications')->insert(['group_id'=>$group->id,'user_id'=>2,'title'=>'Cycle Started','body'=>'New work cycle is now open.','channel'=>'in_app','created_at'=>now(),'updated_at'=>now()]);
        DB::table('audit_logs')->insert(['group_id'=>$group->id,'user_id'=>2,'action'=>'seed.initialized','entity_type'=>'Group','entity_id'=>$group->id,'after'=>json_encode(['cycle_id'=>$cycleId]),'created_at'=>now(),'updated_at'=>now()]);
    }
}
