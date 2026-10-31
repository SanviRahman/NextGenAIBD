<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('title')->nullable();
            $table->text('sort_title')->nullable();
            $table->text('description')->nullable();
            $table->text('video_url')->nullable();
            $table->string('views')->default(0)->nullable();
            $table->string('status')->default(0)->nullable();
            $table->timestamps();
        });

        DB::table('success_stories')->insert([
            [
                'id' => 1,
                'image' => null,
                'title' => "title",
                'sort_title' => "sort_title",
                'description' => "description",
                'video_url' => 'video_url',
                'status' => "1",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
