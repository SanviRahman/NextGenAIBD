<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            // NULL for existing About Us records. Special roles are managed only by Homepage Benefits.
            $table->string('homepage_role', 40)->nullable()->index();
            $table->unsignedInteger('homepage_sort_order')->default(0);
            $table->string('homepage_bg_color', 7)->nullable();
            $table->string('homepage_accent_color', 7)->nullable();
            $table->string('homepage_image_alt', 200)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropIndex(['homepage_role']);
            $table->dropColumn([
                'homepage_role', 'homepage_sort_order', 'homepage_bg_color',
                'homepage_accent_color', 'homepage_image_alt',
            ]);
        });
    }
};
