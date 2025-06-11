<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsMinOtRequirementToOtRateSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ot_rate_setups', function (Blueprint $table) {
            $table->integer('is_min_ot_requirement')->nullable()->after('times_value');
            $table->float('min_ot_time', 10,2)->nullable()->after('is_min_ot_requirement');
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
            $table->dropColumn('is_min_ot_requirement');
            $table->dropColumn('min_ot_time');
        });
    }
}
