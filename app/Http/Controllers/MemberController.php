<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
{
    public function index(): Response
    {
        $groupId = app('currentGroupId');
        return Inertia::render('Members/Index', [
            'members' => Member::forGroup($groupId)->paginate(20),
        ]);
    }

    public function show(Member $member): Response
    {
        $this->authorize('view', $member);

        return Inertia::render('Members/Show', ['member' => $member]);
    }
}
