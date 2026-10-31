<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_spotlight_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 180);
            $table->string('poster_path', 512);
            $table->string('preview_video_path', 512)->nullable();
            $table->string('full_video_provider', 16);
            $table->string('full_video_url', 2048);
            $table->text('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }
    public function down(): void { Schema::dropIfExists('homepage_spotlight_videos'); }
};
