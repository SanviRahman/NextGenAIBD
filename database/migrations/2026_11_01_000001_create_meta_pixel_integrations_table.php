<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meta_pixel_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('pixel_id', 25)->unique();
            $table->longText('pixel_script');
            // Encrypted by the Eloquent model; keep this as TEXT (not varchar).
            $table->text('access_token');
            $table->string('test_event_code', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meta_pixel_integrations');
    }
};
