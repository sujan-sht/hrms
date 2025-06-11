<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOneTimeSettlementToBonusSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus_setups', function (Blueprint $table) {
            $table->integer('one_time_settlement')->default(10)->after('tax_applicable_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus_setups', function (Blueprint $table) {
            $table->dropColumn('one_time_settlement');
        });
    }
}
