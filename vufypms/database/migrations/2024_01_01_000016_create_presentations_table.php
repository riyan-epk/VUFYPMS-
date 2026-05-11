<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->enum('type', ['proposal_defense', 'progress_review', 'final_defense']);
            $table->dateTime('scheduled_at');
            $table->string('venue', 255)->nullable();
            $table->string('online_link', 500)->nullable();
            $table->text('panel_info')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->enum('status', ['scheduled', 'completed', 'postponed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};
