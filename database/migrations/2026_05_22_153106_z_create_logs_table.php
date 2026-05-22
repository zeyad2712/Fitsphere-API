<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->text('notes')->nullable();
            $table->date('date');
            $table->decimal('target_value', 8, 2)->nullable();
            $table->decimal('achieved_value', 8, 2)->nullable();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            // service_id refers to AI Service, which will be created later. Let's use unsignedBigInteger
            $table->unsignedBigInteger('service_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
