<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGradeToGrossSalarySetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gross_salary_setups', function (Blueprint $table) {
            $table->float('grade', 10, 2)->nullable()->after('gross_salary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gross_salary_setups', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
}
