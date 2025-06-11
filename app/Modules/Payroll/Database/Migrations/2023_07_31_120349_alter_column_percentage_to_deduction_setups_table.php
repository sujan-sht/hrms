<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnPercentageToDeductionSetupsTable extends Migration
{
    public function up()
    {
        Schema::table('deduction_setups', function (Blueprint $table) {
            $table->float('percentage',10,8)->nullable()->change();
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
            $table->float('percentage')->change();
        });
    }
}
