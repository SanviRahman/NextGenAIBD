<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keep the original client_reviews table and all existing review records intact.
        Schema::table('client_reviews', function (Blueprint $table) {
            $table->unsignedInteger('homepage_sort_order')->default(0);
            // Historical reviews remain unpublished until explicitly approved by an administrator.
            $table->boolean('homepage_permission_confirmed')->default(false);
            $table->index(['status', 'homepage_permission_confirmed', 'homepage_sort_order'], 'client_reviews_homepage_visible_idx');
        });
    }

    public function down(): void
    {
        Schema::table('client_reviews', function (Blueprint $table) {
            $table->dropIndex('client_reviews_homepage_visible_idx');
            $table->dropColumn(['homepage_sort_order', 'homepage_permission_confirmed']);
        });
    }
};
