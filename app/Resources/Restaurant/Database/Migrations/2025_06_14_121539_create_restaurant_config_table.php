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
        Schema::create('restaurant_config', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('language')->default('en');
            $table->string('primary_color')->default('#000000');
            $table->string('secondary_color')->default('#FFFFFF');
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_config', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
        });

        Schema::dropIfExists('restaurant_config');
    }
};
