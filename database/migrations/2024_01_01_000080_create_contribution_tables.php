<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scoring_rules', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('event_type', 100);
            $table->decimal('base_points', 10, 2);
            $table->decimal('multiplier', 5, 2)->default(1.00);
            $table->json('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('event_type');
            $table->index('is_active');
        });

        Schema::create('contribution_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('rule_id')->constrained('scoring_rules');
            $table->string('event_type', 100);
            $table->decimal('points_earned', 10, 2);
            $table->string('reference_type', 150)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['employee_id', 'occurred_at']);
            $table->index('event_type');
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('contribution_scores', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained();
            $table->decimal('total_points', 12, 2)->default(0);
            $table->decimal('monthly_points', 10, 2)->default(0);
            $table->decimal('quarterly_points', 10, 2)->default(0);
            $table->unsignedInteger('rank_overall')->nullable();
            $table->unsignedInteger('rank_department')->nullable();
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();

            $table->index('total_points');
            $table->index('rank_overall');
        });

        Schema::create('score_adjustment_requests', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('employee_id')->constrained();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->decimal('adjustment_points', 10, 2);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('requested_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_adjustment_requests');
        Schema::dropIfExists('contribution_scores');
        Schema::dropIfExists('contribution_events');
        Schema::dropIfExists('scoring_rules');
    }
};
