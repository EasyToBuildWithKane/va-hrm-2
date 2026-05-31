<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provisioning_requests', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('employee_id')->constrained();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->enum('type', ['onboarding', 'offboarding', 'access_change', 'license_assign']);
            $table->enum('status', ['pending', 'approved', 'active', 'suspended', 'disabled', 'revoked'])->default('pending');
            $table->foreignId('requested_by')->constrained('users');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['employee_id', 'type']);
            $table->index('status');
        });

        Schema::create('account_provisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('provisioning_request_id')->nullable()->constrained('provisioning_requests')->nullOnDelete();
            $table->enum('account_type', ['email', 'system', 'software', 'device']);
            $table->string('account_identifier');
            $table->enum('status', ['pending', 'active', 'suspended', 'disabled', 'revoked'])->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'account_type']);
            $table->index('status');
        });

        Schema::create('email_provisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('account_provision_id')->constrained('account_provisions')->cascadeOnDelete();
            $table->string('email_address');
            $table->string('alias')->nullable();
            $table->enum('mailbox_type', ['standard', 'shared', 'distribution'])->default('standard');
            $table->timestamps();
        });

        Schema::create('software_licenses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('vendor')->nullable();
            $table->string('license_key', 500)->nullable();
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('used_seats')->default(0);
            $table->date('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_software_licenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained();
            $table->foreignId('software_license_id')->constrained();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('assigned_by')->constrained('users');
            $table->timestamps();

            $table->unique(['employee_id', 'software_license_id'], 'uq_emp_license');
        });

        Schema::create('provisioning_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('provisioning_request_id')->nullable()->constrained('provisioning_requests')->nullOnDelete();
            $table->foreignId('employee_id')->constrained();
            $table->string('action', 80);
            $table->string('subject', 150)->nullable();
            $table->enum('result', ['success', 'failure', 'skipped'])->default('success');
            $table->text('message')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provisioning_logs');
        Schema::dropIfExists('employee_software_licenses');
        Schema::dropIfExists('software_licenses');
        Schema::dropIfExists('email_provisions');
        Schema::dropIfExists('account_provisions');
        Schema::dropIfExists('provisioning_requests');
    }
};
