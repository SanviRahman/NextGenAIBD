<?php

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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('type')->nullable();
            $table->string('country')->default(null)->nullable();
            $table->string('phonecode')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('password_confirmation')->nullable();
            $table->string('avatar')->default('avatar.png');
            $table->tinyInteger('status')->default(0)->nullable();
            $table->integer('drafts')->default(0)->nullable();
            $table->timestamps();
        });

        DB::table('admins')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'username' => 'admin',
                'type' => 'admin',
                'country' => 'Bangladesh',
                'phonecode' => '+880',
                'mobile' => 01772413272,
                'email' => 'admin@admin.com',
                'status' => 1,
                'password' => bcrypt('password'),
                'password_confirmation' => 'password',
            ],
            [
                'id' => 2,
                'name' => 'superadmin',
                'username' => 'superadmin',
                'type' => 'superadmin',
                'country' => 'Bangladesh',
                'phonecode' => '+880',
                'mobile' => 01772413272,
                'email' => 'superadmin@superadmin.com',
                'status' => 1,
                'password' => bcrypt('password'),
                'password_confirmation' => 'password',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
