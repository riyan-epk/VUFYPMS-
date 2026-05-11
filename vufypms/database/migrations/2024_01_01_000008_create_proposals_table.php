<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->unique()->constrained('teams')->onDelete('cascade');
            $table->string('title', 500);
            $table->text('abstract');
            $table->foreignId('domain_id')->constrained('project_domains')->onDelete('restrict');
            $table->text('tools_technologies');
            $table->text('objectives')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'revision_required', 'approved', 'rejected'])->default('draft');
            $table->text('revision_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
