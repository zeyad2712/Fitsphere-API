<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rm1_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')->constrained('logs')->cascadeOnDelete();
            $table->decimal('max_weight', 5, 2)->nullable();
            $table->integer('max_rep')->nullable();
            $table->string('muscle_name')->nullable();
            $table->decimal('max_rep_weight', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rm1_logs');
    }
};
