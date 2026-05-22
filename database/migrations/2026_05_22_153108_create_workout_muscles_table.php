<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_muscles', function (Blueprint $table) {
            $table->id();
            // workout_id corresponds to workout_plans (which has plan_id) or is it a separate entity?
            // Assuming workout_id points to workout_plans
            $table->foreignId('workout_id')->constrained('workout_plans')->cascadeOnDelete();
            $table->foreignId('muscle_id')->constrained('muscles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_muscles');
    }
};
