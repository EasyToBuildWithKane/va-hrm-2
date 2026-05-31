<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_number', 20)->unique();
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('position_id')->constrained('positions');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern']);
            $table->enum('employment_status', ['active', 'inactive', 'on_leave', 'terminated', 'resigned'])->default('active');
            $table->date('join_date');
            $table->date('probation_end_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->enum('onboarding_status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->enum('offboarding_status', ['none', 'in_progress', 'completed'])->default('none');
            $table->decimal('salary', 14, 2)->nullable()->comment('Sensitive — redacted in audit');
            $table->string('bank_account_number', 50)->nullable()->comment('Sensitive');
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();
            $table->index('manager_id');
            $table->index('employment_status');
            $table->index(['department_id', 'employment_status']);
        });

        Schema::table('departments', function (Blueprint $table): void {
            $table->foreign('manager_id')->references('id')->on('employees')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table): void {
            $table->dropForeign(['manager_id']);
        });
        Schema::dropIfExists('employees');
    }
};
