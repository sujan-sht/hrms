<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsLoginLogoutLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_logout_logs', function (Blueprint $table) {
            $table->integer('created_user_id')->nullable();
            $table->string('created_user_modal')->nullable();
            $table->integer('action_id')->nullable();
            $table->string('action_model')->nullable();
            $table->text('route')->nullable();
            $table->longText('browser_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
