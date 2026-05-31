<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 20)->unique();
            $table->decimal('days_per_year', 5, 2);
            $table->boolean('is_paid')->default(true);
            $table->boolean('carry_forward')->default(false);
            $table->decimal('max_carry_days', 5, 2)->default(0);
            $table->boolean('requires_docs')->default(false);
            $table->unsignedInteger('min_notice_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('leave_policies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->json('rules')->comment('Eligibility, accrual, blackout periods');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });

        Schema::create('leave_quotas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->decimal('entitled_days', 5, 2);
            $table->decimal('used_days', 5, 2)->default(0);
            $table->decimal('carried_days', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type_id', 'year'], 'uq_emp_type_year');
        });

        Schema::create('leave_requests', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('leave_type_id')->constrained();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days_count', 5, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->json('attachments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_quotas');
        Schema::dropIfExists('leave_policies');
        Schema::dropIfExists('leave_types');
    }
};
