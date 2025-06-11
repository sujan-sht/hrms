<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartialSettlementFieldsToTadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->string('settled_method')->nullable();
            $table->double('settled_amt',14,2)->nullable();
            $table->string('settled_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tadas', function (Blueprint $table) {
            $table->dropColumn('settled_method');
            $table->dropColumn('settled_amt');
            $table->dropColumn('settled_remarks');
        });
    }
}
