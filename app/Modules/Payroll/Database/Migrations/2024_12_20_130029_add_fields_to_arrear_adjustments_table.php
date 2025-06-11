<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToArrearAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('arrear_adjustments', function (Blueprint $table) {
            $table->string('year')->nullable()->after('designation_id');
            $table->string('month')->nullable()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('arrear_adjustments', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
        });
    }
}
