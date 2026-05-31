<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_contracts', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number', 50)->unique();
            $table->enum('contract_type', ['probation', 'fixed_term', 'permanent', 'freelance']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('base_salary', 14, 2)->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
        });

        Schema::create('employee_documents', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('document_type', 50);
            $table->string('title');
            $table->string('file_path');
            $table->string('mime_type', 80)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'document_type']);
        });

        Schema::create('employee_timeline', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('event_type', 80);
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at');
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'occurred_at']);
        });

        Schema::create('employee_emergency_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('relationship', 50);
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_emergency_contacts');
        Schema::dropIfExists('employee_timeline');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_contracts');
    }
};
