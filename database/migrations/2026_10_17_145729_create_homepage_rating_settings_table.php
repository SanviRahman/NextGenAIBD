<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_rating_settings', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 100)->nullable();
            $table->string('heading', 180)->default('Where Clients Find Our Reviews');
            $table->text('description')->nullable();
            $table->string('background_color', 7)->default('#090D14');
            $table->string('accent_color', 7)->default('#C74659');
            $table->unsignedSmallInteger('scroll_seconds')->default(36);
            $table->boolean('autoplay')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('homepage_rating_settings'); }
};
