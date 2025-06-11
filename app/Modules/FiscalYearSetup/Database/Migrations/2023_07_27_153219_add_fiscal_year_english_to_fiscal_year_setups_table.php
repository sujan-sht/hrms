<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiscalYearEnglishToFiscalYearSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->string('fiscal_year_english')->after('fiscal_year')->nullable();
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
            $table->dropColumn('fiscal_year_english');
        });
    }
}
