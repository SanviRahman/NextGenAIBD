<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('internal_name', 140);
            $table->string('main_image_path', 512);
            $table->string('main_image_alt', 220);
            $table->string('background_shape_path', 512)->nullable();
            $table->string('caption_text', 255)->nullable();
            $table->string('caption_animation', 20)->default('reveal');
            $table->unsignedInteger('display_duration_ms')->default(7000);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('homepage_hero_slides');
    }
};
