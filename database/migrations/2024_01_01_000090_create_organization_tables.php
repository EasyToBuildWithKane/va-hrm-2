<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_nodes', function (Blueprint $table): void {
            $table->id();
            $table->enum('node_type', ['employee', 'department', 'role', 'project', 'approval_authority']);
            $table->string('reference_type', 150);
            $table->unsignedBigInteger('reference_id');
            $table->string('label');
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['reference_type', 'reference_id'], 'uq_org_reference');
            $table->index('node_type');
            $table->index('is_active');
        });

        Schema::create('organization_relationships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('from_node_id')->constrained('organization_nodes')->cascadeOnDelete();
            $table->foreignId('to_node_id')->constrained('organization_nodes')->cascadeOnDelete();
            $table->enum('relationship_type', ['REPORT_TO', 'MANAGE', 'BELONG_TO', 'APPROVE_FOR', 'WORK_WITH', 'MEMBER_OF']);
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();

            $table->unique(['from_node_id', 'to_node_id', 'relationship_type'], 'uq_org_relationship');
            $table->index('from_node_id');
            $table->index('to_node_id');
            $table->index('relationship_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_relationships');
        Schema::dropIfExists('organization_nodes');
    }
};
