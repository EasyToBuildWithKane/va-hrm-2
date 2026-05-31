<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_configurations', function (Blueprint $table): void {
            $table->id();
            $table->string('workflow_type', 100)->unique();
            $table->json('config');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('approval_workflows', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('requestable_type', 150);
            $table->unsignedBigInteger('requestable_id');
            $table->string('workflow_type', 100);
            $table->unsignedInteger('current_step')->default(1);
            $table->unsignedInteger('total_steps');
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected', 'cancelled', 'escalated'])->default('in_progress');
            $table->timestamp('sla_deadline_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['requestable_type', 'requestable_id']);
            $table->index('status');
            $table->index('workflow_type');
        });

        Schema::create('approval_steps', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_id')->constrained('approval_workflows')->cascadeOnDelete();
            $table->unsignedInteger('step_number');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('approver_role', 100)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'skipped', 'delegated', 'escalated'])->default('pending');
            $table->timestamp('decision_at')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('delegated_to_id')->nullable();
            $table->unsignedInteger('sla_hours')->default(24);
            $table->timestamp('sla_deadline_at')->nullable();
            $table->timestamps();

            $table->foreign('approver_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('delegated_to_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['workflow_id', 'step_number']);
            $table->index('approver_id');
            $table->index('status');
        });

        Schema::create('approval_decisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('step_id')->constrained('approval_steps')->cascadeOnDelete();
            $table->foreignId('decided_by')->constrained('users');
            $table->enum('decision', ['approve', 'reject', 'delegate', 'escalate']);
            $table->text('notes')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('decided_at');
            $table->timestamps();
        });

        Schema::create('approval_delegations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('step_id')->constrained('approval_steps')->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->constrained('users');
            $table->text('reason')->nullable();
            $table->timestamp('delegated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_delegations');
        Schema::dropIfExists('approval_decisions');
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_workflows');
        Schema::dropIfExists('workflow_configurations');
    }
};
