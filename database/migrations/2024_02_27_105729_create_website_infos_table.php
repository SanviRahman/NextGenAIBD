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
        Schema::create('website_infos', function (Blueprint $table) {
            $table->id();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('number')->nullable();
            $table->string('wattsup_number')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkdin')->nullable();
            $table->string('radit')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('pintarest')->nullable();
            $table->text('sort_descroption')->nullable();
            $table->string('image')->nullable();
            // $table->string('pintarest')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image')->nullable();
            $table->timestamps();
        });

        DB::table('website_infos')->insert([
            [
                'id' => 1,
                'address' => 'address',
                'email' => "email.com",
                'number' => '01777777777777',
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
        Schema::dropIfExists('website_infos');
    }
};
