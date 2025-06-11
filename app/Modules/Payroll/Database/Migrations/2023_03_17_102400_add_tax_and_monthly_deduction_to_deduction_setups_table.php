<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxAndMonthlyDeductionToDeductionSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deduction_setups', function (Blueprint $table) {
            $table->integer('tax_deduction')->nullable();
            $table->integer('monthly_deduction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deduction_setups', function (Blueprint $table) {
            $table->dropColumn('tax_deduction');
            $table->dropColumn('monthly_deduction');
        });
    }
}
