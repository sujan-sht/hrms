<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIncomeTypeToLeaveEncashmentSetups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_encashment_setups', function (Blueprint $table) {
            $table->text('income_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_encashment_setups', function (Blueprint $table) {
            $table->integer('income_type')->change();
        });
    }
}
