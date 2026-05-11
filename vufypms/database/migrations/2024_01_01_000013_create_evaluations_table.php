<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['proposal_defense', 'progress_review', 'final_defense']);
            $table->decimal('marks', 5, 2)->nullable();
            $table->decimal('max_marks', 5, 2)->default(100.00);
            $table->text('remarks')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('evaluation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
