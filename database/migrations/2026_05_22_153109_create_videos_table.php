<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable(); // duration in seconds
            $table->foreignId('category_id')->constrained('video_categories')->cascadeOnDelete();
            $table->foreignId('muscle_id')->nullable()->constrained('muscles')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
