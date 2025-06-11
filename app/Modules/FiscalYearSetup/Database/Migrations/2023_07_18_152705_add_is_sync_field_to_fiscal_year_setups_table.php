<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSyncFieldToFiscalYearSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fiscal_year_setups', function (Blueprint $table) {
            $table->string('is_sync')->default(10)->nullable();

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
            $table->dropColumn('is_sync');
        });
    }
}
