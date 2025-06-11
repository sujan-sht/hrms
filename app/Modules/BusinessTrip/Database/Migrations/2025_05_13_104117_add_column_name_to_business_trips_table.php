<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNameToBusinessTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trips', function (Blueprint $table) {
            $table->string('advance_amount')->nullable();
            $table->string('converted_amount_npr')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_trips', function (Blueprint $table) {
            $table->dropColumn('advance_amount');
            $table->dropColumn('converted_amount_npr');
        });
    }
}
