<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('goal')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            // session_id will be added later or made nullable if session_bookings table isn't created yet
            // Wait, we can add it as integer now and define the foreign key constraint later,
            // but for simplicity let's just make it a foreignId. Since session_bookings isn't created, we will just use foreignId without constrained.
            $table->unsignedBigInteger('session_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
