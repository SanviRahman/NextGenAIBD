<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_consultation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 100)->nullable();
            $table->string('heading', 180)->default('Let’s Talk About Your Project');
            $table->string('image_path', 512)->nullable();
            $table->string('image_alt', 200)->default('Sunnah Digital Agency team collaborating');
            $table->text('description')->nullable();
            $table->string('background_color', 7)->default('#0B0F16');
            $table->string('accent_color', 7)->default('#C74659');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('homepage_consultation_settings');
    }
};
