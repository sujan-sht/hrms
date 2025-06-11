<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesValueToOtRateSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ot_rate_setups', function (Blueprint $table) {
            $table->integer('ot_basis')->nullable()->after('ot_type');
            $table->float('times_value')->nullable()->after('rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ot_rate_setups', function (Blueprint $table) {
            $table->dropColumn('ot_basis');
            $table->dropColumn('times_value');
        });
    }
}
