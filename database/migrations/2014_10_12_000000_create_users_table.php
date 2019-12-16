<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('gender');
            $table->string('unique_key');
            $table->string('role');
            $table->string('type');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('upazilla_id')->unsigned();
            $table->integer('institute_id')->unsigned();
            $table->date('leave_start_date')->nullable();
            $table->date('leave_end_date')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
