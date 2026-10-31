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
        Schema::create('apply_courses', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('number')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('educational_institute')->nullable();
            $table->string('course')->nullable();
            $table->string('course_fee')->nullable();
            $table->string('course_fee_paid')->nullable();
            $table->string('course_fee_unpaid')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_courses');
    }
};
