<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_press_mentions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('logo_path', 512);
            $table->string('website_url', 2048)->nullable();
            $table->string('alt_text', 200)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }
    public function down(): void { Schema::dropIfExists('homepage_press_mentions'); }
};
