<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Depends on the Step-06 homepage_role column. Never recreates or resets abouts.
        Schema::table('abouts', function (Blueprint $table) {
            $table->string('homepage_client_url', 2048)->nullable();
            $table->boolean('homepage_client_permission_confirmed')->default(false);
            $table->unsignedBigInteger('homepage_client_project_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->dropIndex(['homepage_client_project_id']);
            $table->dropColumn([
                'homepage_client_url',
                'homepage_client_permission_confirmed',
                'homepage_client_project_id',
            ]);
        });
    }
};
