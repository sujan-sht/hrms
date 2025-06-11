<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncomeTypeToArrearAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arrear_adjustment_details', function (Blueprint $table) {
            $table->string('income_type')->nullable()->after('arrear_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arrear_adjustment_details', function (Blueprint $table) {
            $table->dropColumn('income_type');
        });
    }
}
