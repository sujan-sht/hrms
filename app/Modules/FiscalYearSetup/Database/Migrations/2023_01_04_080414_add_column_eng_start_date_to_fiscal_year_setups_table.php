<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEngStartDateToFiscalYearSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->date('start_date_english')->after('end_date')->nullable();
            $table->date('end_date_english')->after('start_date_english')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->dropColumn('start_date_english');
            $table->dropColumn('end_date_english');
        });
    }
}
