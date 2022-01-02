<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->datetime('birth');
            $table->text('birth_place');
            $table->boolean('gender')->default(false);
            $table->string('email', 255)->unique();
            $table->string('address', 255);
            $table->string('phone', 20);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('user_type')->default(false);
            $table->boolean('status')->default(false);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
