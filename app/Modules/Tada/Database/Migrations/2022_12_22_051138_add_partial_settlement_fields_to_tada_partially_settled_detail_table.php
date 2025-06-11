<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartialSettlementFieldsToTadaPartiallySettledDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tada_partially_settled_detail', function (Blueprint $table) {
            $table->string('settled_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tada_partially_settled_detail', function (Blueprint $table) {
            $table->dropColumn('settled_method');
        });
    }
}
