<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->string('favicon')->default('favicon.png')->nullable();
            $table->string('frontlogo')->default('frontlogo.png')->nullable();
            $table->string('backlogo')->default('backlogo.png')->nullable();
            $table->timestamps();
        });

        DB::table('logos')->insert([
            [
                'id' => 1,
                'favicon' => 'favicone.png',
                'frontlogo' => 'frontlogo.png',
                'backlogo' => 'backlogo.png',
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
        Schema::dropIfExists('logos');
    }
};
