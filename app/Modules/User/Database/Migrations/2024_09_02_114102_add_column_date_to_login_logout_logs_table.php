<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateToLoginLogoutLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login_logout_logs', function (Blueprint $table) {
            $table->date('date')->nullable()->after('type');
            $table->string('nepali_date')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login_logout_logs', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('nepali_date');
        });
    }
}
