<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreviousIncomeToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->double('total_previous_income',14,2)->nullable()->after('effective_fiscal_year');
            $table->double('total_previous_deduction',14,2)->nullable()->after('total_previous_income');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('total_previous_income');
            $table->dropColumn('total_previous_deduction');
        });
    }
}
