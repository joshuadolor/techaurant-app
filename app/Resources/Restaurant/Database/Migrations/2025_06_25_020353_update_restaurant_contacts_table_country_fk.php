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

        Schema::table('restaurant_contacts', function (Blueprint $table) {
            $table->dropColumn('country');
        });

        Schema::table('restaurant_contacts', function (Blueprint $table) {
            $table->foreignId('country_id')
                  ->nullable()
                  ->constrained('countries')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_contacts', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });

        Schema::table('restaurant_contacts', function (Blueprint $table) {
            // Restore the original column
            $table->string('country')->nullable();
        });
    }
};
