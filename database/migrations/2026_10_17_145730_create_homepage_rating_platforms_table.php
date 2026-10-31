<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homepage_rating_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name', 100);
            $table->string('logo_path', 512);
            $table->string('profile_url', 2048);
            $table->decimal('rating_value', 3, 2)->nullable();
            $table->unsignedInteger('review_count')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }
    public function down(): void { Schema::dropIfExists('homepage_rating_platforms'); }
};
