<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_hero_settings', function (Blueprint $table) {
            $table->id();
            $table->string('primary_cta_label', 70)->default('About Us');
            $table->string('primary_cta_url', 1024)->default('/about');
            $table->string('secondary_cta_label', 70)->default('Contact Us');
            $table->string('secondary_cta_url', 1024)->default('/contact');
            $table->string('background_color', 7)->default('#111B2B');
            $table->string('accent_color', 7)->default('#E6A468');
            $table->string('text_color', 7)->default('#F8FAFC');
            $table->boolean('autoplay_enabled')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('homepage_hero_settings');
    }
};
