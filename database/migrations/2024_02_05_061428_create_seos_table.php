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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('status')->default(1)->nullable();
            $table->timestamps();
        });

        DB::table('seos')->insert([
            [
                'id' => 1,
                'title' => 'title',
                'slug' => Str::slug('slug'),
                'description' => "description",
                'keywords' => 'keywords',
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
        Schema::dropIfExists('seos');
    }
};
