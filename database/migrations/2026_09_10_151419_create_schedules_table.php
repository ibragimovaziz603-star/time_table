<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('day_id')->constrained('days')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->string('lesson_type')->default('lec');
            $table->string('semester')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'day_id', 'period_id', 'semester'], 'uniq_group_slot');
            $table->unique(['teacher_id', 'day_id', 'period_id', 'semester'], 'uniq_teacher_slot');
            $table->unique(['room_id', 'day_id', 'period_id', 'semester'], 'uniq_room_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};