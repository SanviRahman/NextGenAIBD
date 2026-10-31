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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('avatar')->default('avatar.png');
            $table->string('email')->unique()->nullable();
            $table->string('number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('password_confirmation')->nullable();
            $table->text('description')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('gender')->nullable();
            $table->string('status')->default(1)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Abdur Rahman Isha',
                'username' => 'abdurrahmanisha',
                'email' => 'admin@admin.com',
                'number' => '01772413272',
                'password' =>  bcrypt('password'),
                'password_confirmation' => 'password',
                'description' => 'Im Web Programmer',
                'country' => 'Bangladesh',
                'address' => 'Dhaka, Bangladesh',
                'state' => 'North',
                'zipcode' => '8200',
                'city' => 'Dhaka',
                'gender' => 'Male',
                'status' => 1,
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
        Schema::dropIfExists('users');
    }
};
