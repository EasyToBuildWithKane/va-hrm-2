<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->char('ulid', 26)->nullable()->after('id');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('password');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes();

            $table->unique('ulid');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['ulid']);
            $table->dropIndex(['status']);
            $table->dropColumn(['ulid', 'status', 'last_login_at']);
            $table->dropSoftDeletes();
        });
    }
};
