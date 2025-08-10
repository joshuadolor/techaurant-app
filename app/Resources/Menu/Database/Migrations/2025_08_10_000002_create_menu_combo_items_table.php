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
        Schema::create('menu_combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_menu_item_id')->constrained('menu_items')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->nullable()->comment('The quantity of the menu item in the combo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_combo_items', function (Blueprint $table) {
            $table->dropForeign(['main_menu_item_id']);
            $table->dropForeign(['menu_item_id']);
        });

        Schema::dropIfExists('menu_combo_items');
    }
};
