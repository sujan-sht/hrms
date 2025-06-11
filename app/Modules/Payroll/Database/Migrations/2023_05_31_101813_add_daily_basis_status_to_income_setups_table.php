<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyBasisStatusToIncomeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_setups', function (Blueprint $table) {
            $table->integer('daily_basis_status')->default(10)->after('salary_type');
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
            $table->dropColumn('daily_basis_status');
        });
    }
}
