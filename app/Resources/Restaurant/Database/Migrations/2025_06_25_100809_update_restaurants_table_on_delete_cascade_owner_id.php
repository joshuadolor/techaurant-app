<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['owner_id']);
            
            // Recreate the foreign key with cascade delete
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Drop the cascade foreign key
            $table->dropForeign(['owner_id']);
            
            // Restore the original foreign key without cascade
            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
