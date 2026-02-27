<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Loan;
use App\Models\Member;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $groupId = app('currentGroupId');
        $membership = auth()->user()->memberships()->where('group_id', $groupId)->first();

        return Inertia::render('Dashboard/Index', [
            'cards' => [
                'members' => Member::forGroup($groupId)->count(),
                'contributions' => Contribution::forGroup($groupId)->sum('amount'),
                'active_loans' => Loan::forGroup($groupId)->where('status', 'Active')->count(),
            ],
            'myMemberId' => $membership?->member_id,
        ]);
    }
}
