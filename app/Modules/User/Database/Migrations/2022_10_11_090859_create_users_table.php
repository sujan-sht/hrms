<?php

use Illuminate\Support\Facades\Schema;
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

            $table->text('ip_address');
            $table->string('username');
            $table->string('password');
            $table->string('email')->nullable();
            $table->string('user_type');
            $table->string('activation_code')->nullable();
            $table->string('last_login')->nullable();
            $table->boolean('active')->default(0);
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('emp_id')->nullable();
            $table->rememberToken();
            $table->integer('parent_id')->nullable();

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
