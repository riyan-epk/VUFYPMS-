<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['leader', 'member'])->default('member');
            $table->enum('status', ['active', 'left'])->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['team_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
