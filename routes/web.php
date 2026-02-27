<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RepaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

Route::middleware(['auth', 'set.group'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('members', MemberController::class)->only(['index', 'show']);
    Route::post('/loans/{loan}/repayments', [RepaymentController::class, 'store'])->name('repayments.store');

    Route::inertia('/meetings', 'Meetings/Index')->name('meetings.index');
    Route::inertia('/loans', 'Loans/Index')->name('loans.index');
    Route::inertia('/fines', 'Fines/Index')->name('fines.index');
    Route::inertia('/reports', 'Reports/Index')->name('reports.index');

    Route::inertia('/my/contributions', 'Portal/MyContributions')->name('portal.contributions');
    Route::inertia('/my/loans', 'Portal/MyLoans')->name('portal.loans');
    Route::inertia('/my/fines', 'Portal/MyFines')->name('portal.fines');
    Route::inertia('/my/statements', 'Portal/MyStatements')->name('portal.statements');
    Route::inertia('/my/notifications', 'Portal/MyNotifications')->name('portal.notifications');
});
