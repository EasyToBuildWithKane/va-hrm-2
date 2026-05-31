<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->string('auditable_type', 150);
            $table->unsignedBigInteger('auditable_id');
            $table->enum('event', [
                'created', 'updated', 'deleted', 'restored',
                'approved', 'rejected', 'assigned', 'revoked',
                'activated', 'deactivated',
            ]);
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('changed_fields')->nullable();
            $table->unsignedBigInteger('performed_by')->default(0)->comment('0 = system');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->json('context')->nullable();
            $table->boolean('payroll_sensitive')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id']);
            $table->index('performed_by');
            $table->index('event');
            $table->index('created_at');
        };

        Schema::create('audit_logs', $columns);
        Schema::create('audit_logs_archive', $columns);
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs_archive');
        Schema::dropIfExists('audit_logs');
    }
};
