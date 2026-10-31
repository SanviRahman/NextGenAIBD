<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_views', function (Blueprint $table) {
            $table->id();
            $table->string('view')->default(0);
            $table->timestamps();
        });

        DB::table('website_views')->insert([
            [
                'id' => 1,
                'view' => 1,
                'created_at' => Carbon::parse('2023-01-01 01:01:01'),
                'updated_at' => Carbon::parse('2024-01-01 01:01:01'),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_views');
    }
};
