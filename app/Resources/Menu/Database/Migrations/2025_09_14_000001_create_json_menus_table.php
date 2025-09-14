<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('json_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->json('data');
            $table->timestamps();
            $table->unique('restaurant_id');
        });
    }

    public function down(): void
    {
        Schema::table('json_menus', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
        });
        Schema::dropIfExists('json_menus');
    }
};


