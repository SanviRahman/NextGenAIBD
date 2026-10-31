<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_slide_id')->nullable()->constrained('homepage_hero_slides')->nullOnDelete();
            $table->string('title', 120);
            $table->string('subtitle', 255)->nullable();
            $table->string('icon_path', 512)->nullable();
            $table->string('display_value', 60)->nullable();
            $table->string('link_url', 1024)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['hero_slide_id', 'is_active', 'sort_order'], 'hero_highlight_display_idx');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('homepage_highlights');
    }
};
