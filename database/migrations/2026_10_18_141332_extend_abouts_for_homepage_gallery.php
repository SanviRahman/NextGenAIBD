<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Depends on the existing Step-06 homepage_role and homepage_sort_order columns.
        // Reuses abouts; no existing About/Benefit/Client rows are rewritten.
        Schema::table('abouts', function (Blueprint $table) {
            $table->boolean('homepage_gallery_permission_confirmed')->default(false);
            $table->string('homepage_gallery_size_variant', 16)->default('regular');
            $table->date('homepage_gallery_taken_on')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropColumn([
                'homepage_gallery_permission_confirmed',
                'homepage_gallery_size_variant',
                'homepage_gallery_taken_on',
            ]);
        });
    }
};
