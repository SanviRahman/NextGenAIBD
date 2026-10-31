<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_featured_items', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 64);
            $table->string('item_type', 32);
            $table->unsignedBigInteger('item_id');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['section_key', 'item_type', 'item_id'], 'featured_section_type_item_unique');
            $table->index(['section_key', 'is_active', 'sort_order'], 'featured_section_active_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_featured_items');
    }
};
