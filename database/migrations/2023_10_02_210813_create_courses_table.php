<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('sort_description')->nullable();
            $table->text('categories')->nullable();
            $table->text('video_url')->nullable();
            $table->string('price')->default(0)->nullable();
            $table->string('discount')->default(0)->nullable();
            $table->string('duration_class')->nullable();
            $table->string('weekly_class')->nullable();
            $table->string('skill_requirements')->nullable();
            $table->string('total_student')->default(0)->nullable();
            $table->string('language')->nullable();
            $table->string('assignment')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('views')->default(0)->nullable();
            $table->string('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
