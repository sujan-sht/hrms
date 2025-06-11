<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('forwarded_date')->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->dateTime('cancelled_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('approved_date');
            $table->dropColumn('forwarded_date');
            $table->dropColumn('rejected_date');
            $table->dropColumn('cancelled_date');
        });
    }
}
