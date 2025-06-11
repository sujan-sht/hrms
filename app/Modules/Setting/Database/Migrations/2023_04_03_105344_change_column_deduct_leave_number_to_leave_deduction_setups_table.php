<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnDeductLeaveNumberToLeaveDeductionSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_deduction_setups', function (Blueprint $table) {
            $table->string('deduct_leave_number')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_deduction_setups', function (Blueprint $table) {
            $table->integer('deduct_leave_number')->change();
        });
    }
}
