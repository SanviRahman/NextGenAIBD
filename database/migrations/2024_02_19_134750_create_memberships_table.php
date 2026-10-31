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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('number')->nullable();
            $table->string('website_url')->nullable();
            $table->string('page_url')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('total_costing')->nullable();
            $table->string('total_service')->nullable();
            $table->text('sort_description')->nullable();
            $table->text('description')->nullable();
            $table->text('video_url')->nullable();

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
        Schema::dropIfExists('memberships');
    }
};
