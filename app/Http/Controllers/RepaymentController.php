<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Loan;
use App\Models\Repayment;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    public function store(Request $request, Loan $loan, AuditLogService $audit): RedirectResponse
    {
        abort_unless($request->user()->hasRole($loan->group_id, Role::TREASURER), 403);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'penalty_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_at' => ['required', 'date'],
            'idempotency_key' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($data, $loan, $audit) {
            $repayment = Repayment::firstOrCreate(
                ['group_id' => $loan->group_id, 'loan_id' => $loan->id, 'idempotency_key' => $data['idempotency_key']],
                [
                    'member_id' => $loan->member_id,
                    'amount' => $data['amount'],
                    'penalty_amount' => $data['penalty_amount'] ?? 0,
                    'paid_at' => $data['paid_at'],
                ]
            );
            $audit->record('repayment.created', $repayment, null, $repayment->toArray());
        });

        return back()->with('success', 'Repayment recorded successfully.');
    }
}
