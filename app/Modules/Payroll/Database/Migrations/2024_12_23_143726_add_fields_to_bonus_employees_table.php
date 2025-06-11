<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBonusEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus_employees', function (Blueprint $table) {
            $table->float('tds')->after('total_income')->nullable();
            $table->double('payable_salary',16,2)->after('tds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus_employees', function (Blueprint $table) {
            $table->dropColumn('tds');
            $table->dropColumn('payable_salary');
        });
    }
}
