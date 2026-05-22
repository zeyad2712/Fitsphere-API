<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            
            // Make city and street_name nullable for simple registration
            $table->string('city')->nullable()->change();
            $table->string('street_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Note: Changing back to non-nullable requires doctrine/dbal if old laravel, 
            // but in Laravel 12 it might be fine, though not always reversible easily if there's null data.
            $table->string('city')->nullable(false)->change();
            $table->string('street_name')->nullable(false)->change();
        });
    }
};
