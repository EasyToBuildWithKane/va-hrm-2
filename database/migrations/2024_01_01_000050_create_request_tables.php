<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_requests', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('request_type', 100)->comment('leave_request, equipment_request, etc.');
            $table->foreignId('employee_id')->constrained();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->enum('status', ['draft', 'pending', 'in_progress', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->json('payload')->comment('Request type-specific data');
            $table->text('justification')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
            $table->index('request_type');
            $table->index('status');
        });

        Schema::create('equipment_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_request_id')->constrained('workflow_requests')->cascadeOnDelete();
            $table->string('equipment_type', 80);
            $table->string('model')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('reimbursement_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_request_id')->constrained('workflow_requests')->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('category', 80);
            $table->date('expense_date');
            $table->json('receipts')->nullable();
            $table->timestamps();
        });

        Schema::create('software_access_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_request_id')->constrained('workflow_requests')->cascadeOnDelete();
            $table->string('software_name');
            $table->string('access_level', 50)->nullable();
            $table->date('needed_by')->nullable();
            $table->timestamps();
        });

        Schema::create('account_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_request_id')->constrained('workflow_requests')->cascadeOnDelete();
            $table->enum('account_type', ['email', 'system', 'software', 'device']);
            $table->json('access_scopes')->nullable();
            $table->timestamps();
        });

        Schema::create('salary_adjustment_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_request_id')->constrained('workflow_requests')->cascadeOnDelete();
            $table->foreignId('target_employee_id')->constrained('employees');
            $table->decimal('current_salary', 14, 2);
            $table->decimal('proposed_salary', 14, 2);
            $table->date('effective_date');
            $table->text('justification')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_adjustment_requests');
        Schema::dropIfExists('account_requests');
        Schema::dropIfExists('software_access_requests');
        Schema::dropIfExists('reimbursement_requests');
        Schema::dropIfExists('equipment_requests');
        Schema::dropIfExists('workflow_requests');
    }
};
