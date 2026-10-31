<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->string('name', 140);
            $table->string('company_name', 180)->nullable();
            $table->string('designation', 120)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('email', 254)->nullable();
            $table->text('message');
            $table->string('source_page', 255)->default('homepage');
            $table->string('status', 24)->default('new');
            $table->timestamp('consented_at');
            $table->timestamp('responded_at')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};
