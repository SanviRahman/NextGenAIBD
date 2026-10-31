<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_service_settings', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 100)->nullable();
            $table->string('heading', 180)->default('Our Services');
            $table->text('description')->nullable();
            $table->string('button_label', 80)->default('View All');
            $table->string('background_color', 7)->default('#090C13');
            $table->string('card_color', 7)->default('#1A1D22');
            $table->string('accent_color', 7)->default('#C74659');
            $table->unsignedTinyInteger('cards_limit')->default(9);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_service_settings');
    }
};
