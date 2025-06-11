<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonthlyIncomeToIncomeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_setups', function (Blueprint $table) {
            $table->integer('monthly_income')->default(11)->after('daily_basis_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_setups', function (Blueprint $table) {
            $table->dropColumn('monthly_income');
        });
    }
}
