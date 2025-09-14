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
        Schema::create('menu_item_skus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');

            // Grouping name (e.g., "Size") and its value (e.g., "12oz")
            $table->string('name')->comment('e.g. "Size"');
            $table->string('value')->comment('e.g. "12oz"');
            $table->string('description')->nullable();

            // Merchant-facing code for the specific variant
            $table->string('sku')->nullable();

            // If null, fall back to menu_items.price
            $table->decimal('price', 10, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0);

            $table->string('image_url')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('menu_item_id');
            $table->unique(['menu_item_id', 'name', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_item_skus', function (Blueprint $table) {
            $table->dropIndex(['menu_item_id']);
            $table->dropUnique(['menu_item_id', 'name', 'value']);
            $table->dropForeign(['menu_item_id']);
        });

        Schema::dropIfExists('menu_item_skus');
    }
};


