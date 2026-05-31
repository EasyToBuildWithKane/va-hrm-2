<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_delegations', function (Blueprint $table): void {
            $table->id();
            $table->char('ulid', 26)->unique();
            $table->foreignId('delegated_by')->constrained('users');
            $table->foreignId('delegated_to')->constrained('users');
            $table->enum('delegation_type', ['approval', 'role', 'permission']);
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('permission', 100)->nullable();
            $table->string('scope_type', 50)->nullable();
            $table->unsignedBigInteger('scope_id')->nullable();
            $table->timestamp('valid_from');
            $table->timestamp('valid_until');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('delegated_to');
            $table->index(['valid_from', 'valid_until']);
            $table->index('permission');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_delegations');
    }
};
