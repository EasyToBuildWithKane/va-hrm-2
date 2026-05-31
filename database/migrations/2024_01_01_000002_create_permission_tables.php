<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $config = config('permission.table_names');

        Schema::create($config['permissions'], function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create($config['roles'], function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create($config['model_has_permissions'], function (Blueprint $table) use ($config): void {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign('permission_id')->references('id')->on($config['permissions'])->cascadeOnDelete();
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($config['model_has_roles'], function (Blueprint $table) use ($config): void {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign('role_id')->references('id')->on($config['roles'])->cascadeOnDelete();
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create($config['role_has_permissions'], function (Blueprint $table) use ($config): void {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id')->references('id')->on($config['permissions'])->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on($config['roles'])->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        $config = config('permission.table_names');
        Schema::dropIfExists($config['role_has_permissions']);
        Schema::dropIfExists($config['model_has_roles']);
        Schema::dropIfExists($config['model_has_permissions']);
        Schema::dropIfExists($config['roles']);
        Schema::dropIfExists($config['permissions']);
    }
};
