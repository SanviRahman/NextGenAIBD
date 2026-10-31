<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_spotlight_settings', function (Blueprint $table) {
            $table->id();
            $table->string('heading', 180)->default('In the Spotlight');
            $table->string('eyebrow', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('background_color', 7)->default('#0E1724');
            $table->string('accent_color', 7)->default('#F15B45');
            $table->unsignedSmallInteger('logo_scroll_seconds')->default(32);
            $table->boolean('logo_autoplay')->default(true);
            $table->boolean('video_autoplay')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('homepage_spotlight_settings'); }
};
