<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('currency')->default('TZS'); $table->string('theme_color')->default('#0ea5e9'); $table->json('settings')->nullable(); $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email')->unique()->nullable(); $table->string('phone')->unique(); $table->timestamp('email_verified_at')->nullable(); $table->string('password'); $table->string('pin_hash')->nullable(); $table->timestamp('last_login_at')->nullable(); $table->rememberToken(); $table->timestamps();
        });
        Schema::create('members', function (Blueprint $table) {
            $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('member_number'); $table->string('full_name'); $table->string('phone'); $table->string('address')->nullable(); $table->date('join_date'); $table->string('status')->default('active'); $table->json('kyc_data')->nullable(); $table->timestamps();
            $table->unique(['group_id','member_number']); $table->index(['group_id','status']);
        });
        Schema::create('group_memberships', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete(); $table->string('status')->default('active'); $table->timestamps(); $table->unique(['user_id','group_id']);
        });
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('role'); $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete(); $table->timestamp('assigned_at'); $table->unique(['group_id','user_id','role']);
        });
        Schema::create('meetings', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->date('meeting_date'); $table->string('venue')->nullable(); $table->text('agenda')->nullable(); $table->longText('minutes')->nullable(); $table->boolean('is_closed')->default(false); $table->timestamps(); $table->index('group_id'); });
        Schema::create('attendance', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('meeting_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->boolean('present')->default(true); $table->timestamps(); $table->unique(['meeting_id','member_id']); });
        Schema::create('contribution_types', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->decimal('default_amount',12,2)->nullable(); $table->timestamps(); $table->unique(['group_id','name']); });
        Schema::create('loan_products', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->enum('interest_type',['flat','reducing']); $table->decimal('interest_rate',5,2); $table->unsignedInteger('duration_months'); $table->json('rules')->nullable(); $table->timestamps(); });
        Schema::create('loans', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->foreignId('loan_product_id')->constrained()->cascadeOnDelete(); $table->string('status'); $table->decimal('principal',12,2); $table->decimal('interest_rate',5,2); $table->unsignedInteger('duration_months'); $table->timestamp('disbursed_at')->nullable(); $table->timestamp('closed_at')->nullable(); $table->timestamps(); $table->index(['group_id','status']); });
        Schema::create('loan_guarantors', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('loan_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->decimal('guaranteed_amount',12,2); $table->timestamps(); });
        Schema::create('loan_schedules', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('loan_id')->constrained()->cascadeOnDelete(); $table->date('due_date'); $table->decimal('principal_due',12,2); $table->decimal('interest_due',12,2); $table->decimal('penalty_due',12,2)->default(0); $table->decimal('paid_amount',12,2)->default(0); $table->string('status')->default('pending'); $table->timestamps(); });
        Schema::create('receipts', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('receipt_no'); $table->morphs('receiptable'); $table->decimal('amount',12,2); $table->timestamp('issued_at'); $table->timestamps(); $table->unique(['group_id','receipt_no']); });
        Schema::create('contributions', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->foreignId('meeting_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('contribution_type_id')->constrained()->cascadeOnDelete(); $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete(); $table->decimal('amount',12,2); $table->timestamp('paid_at'); $table->timestamps(); $table->index('group_id'); });
        Schema::create('repayments', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('loan_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete(); $table->string('idempotency_key'); $table->decimal('amount',12,2); $table->decimal('penalty_amount',12,2)->default(0); $table->timestamp('paid_at'); $table->timestamps(); $table->unique(['loan_id','idempotency_key']); });
        Schema::create('fine_types', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->decimal('default_amount',12,2)->nullable(); $table->timestamps(); });
        Schema::create('fines', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->foreignId('fine_type_id')->constrained()->cascadeOnDelete(); $table->decimal('amount',12,2); $table->string('reason')->nullable(); $table->string('status')->default('pending'); $table->timestamp('applied_at'); $table->timestamps(); });
        Schema::create('fine_payments', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('fine_id')->constrained()->cascadeOnDelete(); $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete(); $table->decimal('amount',12,2); $table->timestamp('paid_at'); $table->timestamps(); });
        Schema::create('expense_categories', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->timestamps(); });
        Schema::create('expenses', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete(); $table->decimal('amount',12,2); $table->string('narration'); $table->string('receipt_path')->nullable(); $table->timestamp('incurred_at'); $table->timestamps(); });
        Schema::create('incomes', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->decimal('amount',12,2); $table->string('source'); $table->timestamp('received_at'); $table->timestamps(); });
        Schema::create('work_cycles', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->date('start_date'); $table->date('end_date'); $table->string('frequency'); $table->string('status')->default('open'); $table->timestamps(); });
        Schema::create('dividends', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->constrained()->cascadeOnDelete(); $table->foreignId('work_cycle_id')->constrained()->cascadeOnDelete(); $table->foreignId('member_id')->constrained()->cascadeOnDelete(); $table->decimal('amount',12,2); $table->timestamps(); });
        Schema::create('audit_logs', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->string('action'); $table->string('entity_type'); $table->unsignedBigInteger('entity_id')->nullable(); $table->json('before')->nullable(); $table->json('after')->nullable(); $table->ipAddress('ip_address')->nullable(); $table->timestamps(); $table->index(['group_id','created_at']); });
        Schema::create('notifications', function (Blueprint $table) { $table->id(); $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->text('body'); $table->string('channel')->default('in_app'); $table->timestamp('read_at')->nullable(); $table->timestamps(); });
    }

    public function down(): void
    {
        foreach (['notifications','audit_logs','dividends','work_cycles','incomes','expenses','expense_categories','fine_payments','fines','fine_types','repayments','contributions','receipts','loan_schedules','loan_guarantors','loans','loan_products','contribution_types','attendance','meetings','role_assignments','group_memberships','members','users','groups'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
